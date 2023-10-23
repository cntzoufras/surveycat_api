<?php
    
    namespace App\Services;
    
    use App\Models\User;
    use App\Models\UserRefreshToken;
    
    use App\Models\Users\RefreshToken;
    use App\Repositories\UserRepository;
    use App\Repositories\SavedTableRepository;
    use App\Notifications\UserPasswordReset;
    use Carbon\Carbon;
    use Illuminate\Auth\AuthenticationException;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;
    use Users\Enums\UserType;
    use Utils\RegexUtils;
    
    class AuthService {
        
        private $users;
        
        
        /**
         * @param \App\Repositories\UserRepository $users
         */
        public function __construct(UserRepository $users) {
            
            $this->users = $users;
        }
        
        
        /**
         * @param string $username
         * @param string $password
         *
         * @return \App\Models\User|null
         */
        public function authenticateByCredentials(string $username, string $password): ?User {
            //
            return null;
        }
        
        /**
         * @param \App\Models\User $user
         * @param bool $sneaky
         *
         * @return string
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         */
        public function getToken(User $user, bool $sneaky = false): string {
//            $mailService = app()->make(MailingListService::class);
//            if (!$sneaky && $user->exists) $user->user->touchLogin($mailService);
            
            $scAdminUuid = $this->context->superAdmin()->uuid;
            if ($user->isSurveycatAdmin) {
                return is_null($user->isSurveyCatAdmin)
                    ? $this->createSurveycatAdminToken($user->id, $scAdminUuid)
                    : $this->createSurveycatUserToken($user->scAdmId, $scAdminUuid);
            }
            
            return $this->createUserToken($user->id, $scAdminUuid);
        }
        
        /**
         * Returns the
         *
         * @param string $refreshToken
         *
         * @return User
         * @throws \Illuminate\Auth\AuthenticationException
         * @throws \Throwable
         */
        public function getUserFromRefreshToken(string $refreshToken): User {
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
            
            $isUser = $matched != null && !$matched->isExpired();
            $isSurveycatUser = $userMatched != null && !$userMatched->isExpired();
            if (!$isUser && !$isSurveycatUser) {
                throw new AuthenticationException(trans('auth.failed'));
            }
            
            $token = $matched != null ? $matched : $userMatched;
            $token->active = false;
            $token->save();
            
            return $isUser ? $token->member : $this->members->getSurveycatUser($token->user);
        }
        
        /**
         * @param \App\Models\User $user
         * @param $accessToken
         *
         * @return string
         */
        public function getRefreshToken(User $user, $accessToken): string {
            $token = $user->id == null ?
                new UserRefreshToken(['user_id' => $user->oceanUserId]) :
                new RefreshToken(['member_id' => $user->id]);
            
            $jti = $this->getTokenId($accessToken);
            
            $token->issued_at = Carbon::now();
            $token->jti = $jti;
            $token->save();
            return $this->createRefreshToken(
                $user->id,
                $this->context->superAdmin()->uuid,
                $token->jti
            );
        }
        
        /**
         * @param \App\Models\User $user
         * @param $username
         * @param $password
         *
         * @return \App\Models\User
         */
        public function register(User $user, $username, $password) {
            if (!$user->getNeedsCredentialsAttribute()) {
                throw new InvalidOperationException('member_already_registered');
            }
            $this->validateUsername($username);
            $this->validateRegexPassword($password);
            
            $user = $user->user ?? new UserUser();
            $user->member_id = $user->id;
            $user->username = $username;
            $user->password = Hash::make($password);
            $user->can_login = true;
            DB::transaction(function () use ($user) {
                $user->save();
            });
            return $user;
        }
        
        /**
         * @param $username
         *
         * @return bool
         */
        public function requestPasswordReset($username) {
            $user = $this->members->findByUsername($username);
            if ($user == null || !$user->can_login || $user->is_archived || $user->user == null) {
                return false;
            }
            
            $user = $user->user;
            $user->password_reset_token = Str::random(64);
            $user->password_reset_at = Carbon::now();
            
            DB::transaction(function () use ($user) {
                $user->save();
                $user->notify(new UserPasswordReset($this->context->superAdmin(), $user));
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
            $user = $this->members->findByResetToken($token);
            if ($user == null || !$user->can_login || $user->is_archived || $user->user == null) {
                throw new InvalidOperationException('password_reset_invalid');
            }
            $user = $user->user;
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
         * @param \App\Models\User $user
         * @param array $params
         *
         * @return void
         */
        public function changeCredentials(User $user, array $params) {
            $user = $user->user;
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
         * @param \App\Models\User $user
         *
         * @return void
         */
        public function revokeAccess(User $user) {
            if (!$user->can_login) {
                throw new InvalidOperationException('member_cannot_login');
            }
            $user->can_login = false;
            $user->settings = null;
            
            DB::transaction(function () use ($user) {
                $user->save();
                
                $user->user()->update([
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
            
            $exists = User::query()
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
