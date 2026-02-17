<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepo,
        protected ProfileRepository $profileRepo,
        protected RoleRepository $roleRepo
    ) {}

    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = $this->userRepo->create([
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id'  => $this->roleRepo->getByName('User')->id,
            ]);

            $profile = $this->profileRepo->create([
                'user_id' => $user->id,
                'name'    => $data['name'],
                'phone'   => $data['phone'] ?? null,
                'photo'   => $data['photo'] ?? null,
            ]);
        });
    }

    public function login(array $data)
    {
        $user = $this->userRepo->getByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception('Invalid credentials', 401);
        }

        $user->load('role', 'profile');

        $privileges = $user->role ? explode(',', $user->role->privileges) : [];

        $accessToken = $user->createToken('access_token', $privileges);
        $accessToken->accessToken->expires_at = Carbon::now()->addMinutes(config('sanctum.access_token_expiration'));
        $accessToken->accessToken->save();

        $refreshToken = $user->createToken('refresh_token', ['refresh-token']);
        $refreshToken->accessToken->expires_at = Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'));
        $refreshToken->accessToken->save();

        return [
            'user'  => $user,
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];
    }

    public function refreshToken(string $plainTextToken)
    {
        $tokenInstance = PersonalAccessToken::findToken($plainTextToken);
    
        if (!$tokenInstance || !$tokenInstance->can('refresh-token')) {
            throw new \Exception('Invalid refresh token.');
        }
    
        if ($tokenInstance->expires_at && $tokenInstance->expires_at->isPast()) {
            $tokenInstance->delete();
            throw new \Exception('Refresh token expired.');
        }
    
        $user = $tokenInstance->tokenable;
        $user->load('role');
    
        $tokenInstance->delete();

        $privileges = $user->role ? explode(',', $user->role->privileges) : [];

        $accessToken = $user->createToken('access_token', $privileges);
        $accessToken->accessToken->expires_at = Carbon::now()->addMinutes(config('sanctum.access_token_expiration'));
        $accessToken->accessToken->save();

        $refreshToken = $user->createToken('refresh_token', ['refresh-token']);
        $refreshToken->accessToken->expires_at = Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'));
        $refreshToken->accessToken->save();
    
        return [
            'access_token'  => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];
    }

    public function logout($currentAccessToken, ?string $currentRefreshToken = null)
    {
        $currentAccessToken->delete();

        if ($currentRefreshToken) {
            $tokenInstance = PersonalAccessToken::findToken($currentRefreshToken);
            if ($tokenInstance && $tokenInstance->can('refresh-token')) {
                $tokenInstance->delete();
            }   
        }

        return true;
    }
}