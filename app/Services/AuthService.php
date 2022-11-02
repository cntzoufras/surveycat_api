<?php

namespace App\Services;

use App\Domain\Models\Surveycat\UserRefreshToken;
use App\Domain\Models\Members\Member;
use App\Domain\Models\Members\MemberUser;
use App\Domain\Models\Members\RefreshToken;
use App\Domain\Repositories\Surveycat\UserRepository;
use App\Domain\Repositories\Members\MemberRepository;
use App\Domain\Repositories\SavedTableRepository;
use App\Http\Auth\TenantContext;
use App\Notifications\MemberPasswordReset;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Members\Enums\MemberType;
use Utils\RegexUtils;

class AuthService {

    private $users;


    /**
     * @param \App\Domain\Repositories\Surveycat\UserRepository $users
     */
    public function __construct(UserRepository $users) {

        $this->users = $users;
    }


    /**
     * @param string $username
     * @param string $password
     *
     * @return \App\Domain\Models\Members\Member|null
     */
    public function authenticateByCredentials(string $username, string $password): ?Member {
        //
        return null;
    }

    /**
     * @param \App\Models\Members\Member $member
     * @param bool $sneaky
     *
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getToken(Member $member, bool $sneaky = false): string {
        $mailService = app()->make(MailingListService::class);
        if (!$sneaky && $member->exists) $member->user->touchLogin($mailService);

        $scAdminUuid = $this->context->superAdmin()->uuid;
        if ($member->isSurveycatAdmin) {
            return is_null($member->isSurveyCatAdmin)
                ? $this->createSurveycatAdminToken($member->id, $scAdminUuid)
                : $this->createSurveycatUserToken($member->scAdmId, $scAdminUuid);
        }

        return $this->createMemberToken($member->id, $scAdminUuid);
    }

    /**
     * Returns the
     *
     * @param string $refreshToken
     *
     * @return Member
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Throwable
     */
    public function getMemberFromRefreshToken(string $refreshToken): Member {
        $jti = $this->getTokenId($refreshToken);
        /** @var RefreshToken $matched */
        $matched = RefreshToken::query()->where([
            'jti'    => $jti,
            'active' => true,
        ])->first();

        /** @var UserRefreshToken $userMatched */
        $userMatched = UserRefreshToken::query()->where([
            'authentication_type' => $jti,
            'active'              => true,
        ])->first();

        $isMember = $matched != null && !$matched->isExpired();
        $isSurveycatUser = $userMatched != null && !$userMatched->isExpired();
        if (!$isMember && !$isSurveycatUser) {
            throw new AuthenticationException(trans('auth.failed'));
        }

        $token = $matched != null ? $matched : $userMatched;
        $token->active = false;
        $token->save();

        return $isMember ? $token->member : $this->members->getSurveycatMember($token->user);
    }

    /**
     * @param \App\Domain\Models\Members\Member $member
     * @param $accessToken
     *
     * @return string
     */
    public function getRefreshToken(Member $member, $accessToken): string {
        $token = $member->id == null ?
            new UserRefreshToken(['user_id' => $member->oceanUserId]) :
            new RefreshToken(['member_id' => $member->id]);

        $jti = $this->getTokenId($accessToken);

        $token->issued_at = Carbon::now();
        $token->jti = $jti;
        $token->save();
        return $this->createRefreshToken(
            $member->id,
            $this->context->superAdmin()->uuid,
            $token->jti
        );
    }

    /**
     * @param \App\Domain\Models\Members\Member $member
     * @param $username
     * @param $password
     *
     * @return \App\Domain\Models\Members\Member
     */
    public function register(Member $member, $username, $password) {
        if (!$member->getNeedsCredentialsAttribute()) {
            throw new InvalidOperationException('member_already_registered');
        }
        $this->validateUsername($username);
        $this->validateRegexPassword($password);

        $user = $member->user ?? new MemberUser();
        $user->member_id = $member->id;
        $user->username = $username;
        $user->password = Hash::make($password);
        $member->can_login = true;
        DB::transaction(function () use ($user, $member) {
            $member->save();
            $user->save();
            $member->invitation()->delete();
        });
        return $member;
    }

    /**
     * @param $username
     *
     * @return bool
     */
    public function requestPasswordReset($username) {
        $member = $this->members->findByUsername($username);
        if ($member == null || !$member->can_login || $member->is_archived || $member->user == null) {
            return false;
        }

        $user = $member->user;
        $user->password_reset_token = Str::random(64);
        $user->password_reset_at = Carbon::now();

        DB::transaction(function () use ($user, $member) {
            $user->save();
            $member->notify(new MemberPasswordReset($this->context->superAdmin(), $member));
        });

        return true;
    }

    /**
     * @param $token
     * @param $password
     *
     * @return void
     */
    public function resetPassword($token, $password) {
        $member = $this->members->findByResetToken($token);
        if ($member == null || !$member->can_login || $member->is_archived || $member->user == null) {
            throw new InvalidOperationException('password_reset_invalid');
        }
        $user = $member->user;
        if ($user->isPasswordResetExpired()) {
            throw new InvalidOperationException('password_reset_expired');
        }
        $this->validateRegexPassword($password);

        $user->password = Hash::make($password);
        $user->password_reset_token = null;
        $user->password_reset_at = null;
        $user->save();
    }

    /**
     * @param \App\Domain\Models\Members\Member $member
     * @param array $params
     *
     * @return void
     */
    public function changeCredentials(Member $member, array $params) {
        $user = $member->user;
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

    /**
     * @param \App\Domain\Models\Members\Member $member
     *
     * @return void
     */
    public function revokeAccess(Member $member) {
        if (!$member->can_login) {
            throw new InvalidOperationException('member_cannot_login');
        }
        $member->can_login = false;
        $member->settings = null;

        DB::transaction(function () use ($member) {
            $member->save();

            $member->user()->update([
                'username' => null,
                'password' => null,
            ]);
        });
    }

    /**
     * @param $token
     *
     * @return void
     */
    public function logout($token) {
        $jti = $this->getTokenId($token);
        RefreshToken::query()->where('jti', $jti)->update(['active' => false]);
    }

    /**
     * @param $username
     * @param $existing_user
     *
     * @return void
     */
    private function validateUsername($username, $existing_user = null) {
        if (!is_null($existing_user) && $username == $existing_user->username) return;

        $exists = Member::query()
                        ->where('username', $username)
                        ->exists();
        if ($exists) {
            throw new InvalidOperationException("username_exists");
        }
    }

    /**
     * @param $input
     *
     * @return void
     */
    private function validateRegexPassword($input) {
        if (!RegexUtils::validateRegexPassword($input)) {
            throw new InvalidOperationException("weak_password");
        };
    }
}
