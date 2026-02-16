<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Requests\AuthRequest;
use App\Resources\AuthResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;

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

            return $this->success('User registered successfully', null);
        } catch (\Exception $e) {
            return $this->error('Failed to register user', $e->getMessage(), $e->getCode());
        }
    }

    public function login(AuthRequest $request)
    {
        try {
            $user = $this->authService->login($request->all());

            return $this->success('User logged in successfully', new AuthResource($user));
        } catch (\Exception $e) {
            return $this->error('Failed to login user', $e->getMessage(), $e->getCode());
        }
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->header('X-Refresh-Token');

        if (!$refreshToken) {
            return $this->error('Refresh token is required', null, 401);
        }

        try {
            $tokens = $this->authService->refreshToken($refreshToken);
            
            return $this->success('Tokens refreshed successfully', new AuthResource($tokens));
        } catch (\Exception $e) {
            return $this->error('Failed to refresh tokens', $e->getMessage(), $e->getCode());
        }
    }

    public function logout(Request $request)
    {
        $currentToken = $request->user()->currentAccessToken();

        if (!$currentToken) {
            return $this->error('Unauthorized', null, 401);
        }

        $this->authService->logout($currentToken);

        return $this->success('User logged out successfully', null);
    }
}
