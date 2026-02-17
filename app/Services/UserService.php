<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        protected UserRepository $userRepo,
        protected ProfileRepository $profileRepo
    ) {}

    public function getAll()
    {
        return $this->userRepo->getAll();
    }

    public function getById(string $id)
    {
        $user = $this->userRepo->getById($id);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        $user->load('role', 'profile');

        return $user;
    }

    public function update(array $data, string $id)
    {
        $user = $this->userRepo->getById($id);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        $profile = $this->profileRepo->getByUserId($user->id);
        if (!$profile) {
            throw new \Exception('Profile not found', 404);
        }

        return DB::transaction(function () use ($data, $profile, $user) {
            $user['profile'] = $this->profileRepo->update([
                'name'    => $data['name'],
                'phone'   => $data['phone'],
                'photo'   => $data['photo'],
            ], $profile->id);

            return $user;
        });
    }

    public function delete(string $id)
    {
        $user = $this->userRepo->getById($id);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        return DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $this->userRepo->delete($user->id);
        });
    }
}