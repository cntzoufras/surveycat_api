<?php

namespace App\Services;

use App\Domain\Models\Surveycat\UserRefreshToken;
use App\Domain\Models\Parties\Party;
use App\Domain\Models\Parties\PartyUser;
use App\Domain\Models\Parties\RefreshToken;
use App\Domain\Repositories\Surveycat\UserRepository;
use App\Domain\Repositories\Parties\PartyRepository;
use App\Domain\Repositories\SavedTableRepository;
use App\Http\Auth\TenantContext;
use App\Notifications\PartyPasswordReset;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use OrcaTools\Parties\Enums\PartyType;
use OrcaTools\Utils\RegexUtils;
use WebThatMatters\LaravelUtils\Exceptions\InvalidOperationException;

class AuthService {

    private $context, $users;

    const BLACKLIST_PREFIX = 'session-';

    public function __construct(UserRepository $users) {

        $this->users = $users;
    }

    /**
     * Try to authenticate a party using a username/password combination.
     * Surveycat admins can only login by Apparatus in Orca.
     *
     * @param string $username
     * @param string $password
     *
     * @return \App\Domain\Models\Parties\Party The authenticated party
     * @throws \Illuminate\Auth\AuthenticationException If the credentials can't match a record
     */
    public function authenticateByCredentials(string $username, string $password): Party {
        $this->context->switchToTenantSchema();
        $party = $this->parties->findByUsername($username);
        if ($party == null || !Hash::check($password, $party->user->password) || !$party->can_login || $party->type == PartyType::CREW || $party->is_archived) {
            throw new AuthenticationException(trans('auth.failed'));
        }
        return $party;
    }

    public function getToken(Party $party, bool $sneaky = false): string {
        $mailService = app()->make(MailingListService::class);
        if (!$sneaky && $party->exists) $party->user->touchLogin($mailService);

        $orgUuid = $this->context->organization()->uuid;
        if ($party->isSurveycatAdmin) {
            return is_null($party->oceanUserId)
                ? $this->createSurveycatAdminToken($party->id, $orgUuid)
                : $this->createSurveycatUserToken($party->oceanUserId, $orgUuid);
        }

        return $this->createPartyToken($party->id, $orgUuid);
    }

    /**
     * Returns the party of a refresh token, invalidating it.
     *
     * @param string $refreshToken
     *
     * @return Party
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Throwable
     */
    public function getPartyFromRefreshToken(string $refreshToken): Party {
        $jti = $this->getTokenId($refreshToken);
        /** @var RefreshToken $matched */
        $matched = RefreshToken::query()->where([
            'jti'    => $jti,
            'active' => true,
        ])->first();

        /** @var UserRefreshToken $userMatched */
        $userMatched = UserRefreshToken::query()->where([
            'jti'    => $jti,
            'active' => true,
        ])->first();

        $isParty = $matched != null && !$matched->isExpired();
        $isSurveycatUser = $userMatched != null && !$userMatched->isExpired();
        if (!$isParty && !$isSurveycatUser) {
            throw new AuthenticationException(trans('auth.failed'));
        }

        $token = $matched != null ? $matched : $userMatched;
        $token->active = false;
        $token->save();

        return $isParty ? $token->party : $this->parties->getSurveycatParty($token->user);
    }

    public function getRefreshToken(Party $party, $accessToken): string {
        $token = $party->id == null ?
            new UserRefreshToken(['user_id' => $party->oceanUserId]) :
            new RefreshToken(['party_id' => $party->id]);

        $jti = $this->getTokenId($accessToken);

        $token->issued_at = Carbon::now();
        $token->jti = $jti;
        $token->save();
        return $this->createRefreshToken(
            $party->id,
            $this->context->organization()->uuid,
            $token->jti
        );
    }

    public function register(Party $party, $username, $password) {
        if (!$party->getNeedsCredentialsAttribute()) {
            throw new InvalidOperationException('party_already_registered');
        }
        $this->validateUsername($username);
        $this->validateRegexPassword($password);

        $user = $party->user ?? new PartyUser();
        $user->party_id = $party->id;
        $user->username = $username;
        $user->password = Hash::make($password);
        $party->can_login = true;
        DB::transaction(function () use ($user, $party) {
            $party->save();
            $user->save();
            $this->createDefaultFilters($party->id);
            $party->invitation()->delete();
        });
        return $party;
    }

    public function requestPasswordReset($username) {
        $party = $this->parties->findByUsername($username);
        if ($party == null || !$party->can_login || $party->is_archived || $party->user == null) {
            return false;
        }

        $user = $party->user;
        $user->password_reset_token = Str::random(64);
        $user->password_reset_at = Carbon::now();

        DB::transaction(function () use ($user, $party) {
            $user->save();
            $party->notify(new PartyPasswordReset($this->context->organization(), $party));
        });

        return true;
    }

    public function resetPassword($token, $password) {
        $party = $this->parties->findByResetToken($token);
        if ($party == null || !$party->can_login || $party->is_archived || $party->user == null) {
            throw new InvalidOperationException('password_reset_invalid');
        }
        $user = $party->user;
        if ($user->isPasswordResetExpired()) {
            throw new InvalidOperationException('password_reset_expired');
        }
        $this->validateRegexPassword($password);

        $user->password = Hash::make($password);
        $user->password_reset_token = null;
        $user->password_reset_at = null;
        $user->save();
    }

    public function changeCredentials(Party $party, array $params) {
        $user = $party->user;
        if (isset($params['username'])) {
            $this->validateUsername($params['username'], $user);
            $user->username = $params['username'];
        }
        if (isset($params['password'])) {
            $this->validateRegexPassword($params['password']);
            $user->password = Hash::make($params['password']);
        }
        $user->save();
    }

    public function revokeAccess(Party $party) {
        if (!$party->can_login) {
            throw new InvalidOperationException('party_cannot_login');
        }
        $party->can_login = false;
        $party->permission_template_id = null;
        $party->settings = null;

        DB::transaction(function () use ($party) {
            $party->save();

            $party->user()->update([
                'username' => null,
                'password' => null,
            ]);
        });
    }

    public function logout($token) {
        $jti = $this->getTokenId($token);
        Cache::put(self::BLACKLIST_PREFIX . $jti, $token, config('cache.default_ttl'));
        RefreshToken::query()->where('jti', $jti)->update(['active' => false]);
    }

    private function createDefaultFilters($party_id) {
        $assigned_to_me = [
            'table_label' => 'jobs_list',
            'party_id'    => $party_id,
            'name'        => 'Assigned to me',
            'filters'     => [
                [
                    'name'      => 'assignee_id',
                    'operation' => 'oneOf',
                    'value'     => [$party_id],
                ],
            ],
        ];
        $watching = [
            'table_label' => 'jobs_list',
            'party_id'    => $party_id,
            'name'        => 'Watching',
            'filters'     => [
                [
                    'name'      => 'is_watcher',
                    'operation' => '=',
                    'value'     => 'true',
                ],
            ],
        ];

        $saved_table_repository = new SavedTableRepository;
        $saved_table_repository->store($assigned_to_me);
        $saved_table_repository->store($watching);
    }

    private function validateUsername($username, $existing_user = null) {
        if (!is_null($existing_user) && $username == $existing_user->username) return;

        $exists = PartyUser::query()
                           ->where('username', $username)
                           ->exists();
        if ($exists) {
            throw new InvalidOperationException("username_exists");
        }
    }

    private function validateRegexPassword($input) {
        if (!RegexUtils::validateRegexPassword($input)) {
            throw new InvalidOperationException("weak_password");
        };
    }
}
