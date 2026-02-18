<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Requests\AuthRequest;
use App\Resources\AuthResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Lang;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthService $authService
    ) {}
    
    public function register(AuthRequest $request)
    {
        try {
            $user = $this->authService->register($request->all());

            return $this->success(Lang::get('auth.register_success'), null);
        } catch (\Exception $e) {
            return $this->error(Lang::get('auth.register_failed'), $e->getMessage(), $e->getCode());
        }
    }

    public function login(AuthRequest $request)
    {
        try {
            $user = $this->authService->login($request->all());

            return $this->success(Lang::get('auth.login_success'), new AuthResource($user));
        } catch (\Exception $e) {
            return $this->error(Lang::get('auth.login_failed'), $e->getMessage(), $e->getCode());
        }
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->header('X-Refresh-Token');

        if (!$refreshToken) {
            return $this->error(Lang::get('auth.refresh_token_required'), null, 401);
        }

        try {
            $tokens = $this->authService->refreshToken($refreshToken);
            
            return $this->success(Lang::get('auth.refresh_token_success'), new AuthResource($tokens));
        } catch (\Exception $e) {
            return $this->error(Lang::get('auth.refresh_token_failed'), $e->getMessage(), $e->getCode());
        }
    }

    public function logout(Request $request)
    {
        $currentAccessToken = $request->user()->currentAccessToken();
        if (!$currentAccessToken) {
            return $this->error(Lang::get('auth.unauthorized'), null, 401);
        }

        $currentRefreshToken = $request->header('X-Refresh-Token');
        if (!$currentRefreshToken) {
            return $this->error(Lang::get('auth.refresh_token_required'), null, 401);
        }

        try {
            $this->authService->logout($currentAccessToken, $currentRefreshToken);

            return $this->success(Lang::get('auth.logout_success'), null);
        } catch (\Exception $e) {
            return $this->error(Lang::get('auth.logout_failed'), $e->getMessage(), $e->getCode());
        }
    }
}
