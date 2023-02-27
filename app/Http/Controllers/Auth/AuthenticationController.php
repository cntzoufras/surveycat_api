<?php
    
    namespace App\Http\Controllers\Auth;
    
    use App\Http\Controllers\Controller;
    use App\Services\AuthService;
    use Illuminate\Http\Request;
    
    class AuthenticationController extends Controller {
        
        private $auth;
        
        public function __construct(AuthService $service) {
            $this->auth = $service;
        }
        
        /**
         * Attempt a login using a username/password combination
         *
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\JsonResponse
         * @throws \Illuminate\Auth\AuthenticationException
         * @throws \Illuminate\Validation\ValidationException
         */
        public function login(Request $request) {
            $this->validate($request, [
                'username' => 'required',
                'password' => 'required',
            ]);
            $party = $this->auth->authenticateByCredentials(
                $request->input('username'),
                $request->input('password')
            );
            $token = $this->auth->getToken($party);
            
            return response()->json([
                'token'         => $token,
                'refresh_token' => $this->auth->getRefreshToken($party, $token),
            ]);
        }
        
        public function logout(Request $request) {
            $this->auth->logout($request->bearerToken());
            response()->json([
                'message' => 'Logged out successfully.',
            ]);
        }
        
        /**
         * Refresh the credentials of a party, using their refresh token
         *
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\JsonResponse
         * @throws \Illuminate\Auth\AuthenticationException
         * @throws \Illuminate\Validation\ValidationException
         * @throws \Throwable
         */
        public function refresh(Request $request) {
            $this->validate($request, [
                'refresh_token' => 'required|string',
            ]);
            $party = $this->auth->getPartyFromRefreshToken($request->input('refresh_token'));
            $token = $this->auth->getToken($party, true);
            
            return response()->json([
                'token'         => $token,
                'refresh_token' => $this->auth->getRefreshToken($party, $token),
            ]);
        }
        
        public function forgotPassword(Request $request) {
            $this->validate($request, [
                'username' => 'required',
            ]);
            $this->auth->requestPasswordReset($request->input('username'));
            return response()->json([
                'message' => trans('passwords.reset_requested'),
            ]);
        }
        
        public function resetPassword(Request $request) {
            $this->validate($request, [
                'token'                 => 'required',
                'password'              => 'required',
                'password_confirmation' => 'required|same:password',
            ]);
            $this->auth->resetPassword($request->input('token'), $request->input('password'));
            return response()->json([
                'message' => trans('passwords.reset'),
            ]);
        }
        
        public function register(Request $request) {
            $this->validate($request, [
                'token'                 => 'required',
                'username'              => 'required',
                'password'              => 'required',
                'password_confirmation' => 'required|same:password',
            ]);
            $this->auth->register($request->input('username'), $request->input('password'));
            return response()->json(['message' => 'Registered Successfully']);
        }
    }
