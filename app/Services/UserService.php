<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\ProfileRepository;

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
        return $this->userRepo->getById($id);
    }
}