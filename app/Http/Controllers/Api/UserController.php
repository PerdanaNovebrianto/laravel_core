<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Resources\UserResource;
use App\Requests\UserRequest;
use App\Services\UserService;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected UserService $userService
    ) {}
    
    public function all(Request $request)
    {
        try {
            $users = $this->userService->getAll();

            return $this->success('Success to fetch users', UserResource::collection($users));
        } catch (\Exception $e) {
            return $this->error('Failed to fetch users', $e->getMessage(), $e->getCode());
        }
    }

    public function detail(string $id)
    {
        try {
            $user = $this->userService->getById($id);

            return $this->success('Success to fetch user', new UserResource($user));
        } catch (\Exception $e) {
            return $this->error('Failed to fetch user', $e->getMessage(), $e->getCode());
        }
    }

    public function update(UserRequest $request, string $id)
    {
        try {
            $user = $this->userService->update($request->all(), $id);

            return $this->success('Success to update user', new UserResource($user));
        } catch (\Exception $e) {
            return $this->error('Failed to update user', $e->getMessage(), $e->getCode());
        }
    }

    public function delete(string $id)
    {
        try {
            $this->userService->delete($id);
            
            return $this->success('Success to delete user');
        } catch (\Exception $e) {
            return $this->error('Failed to delete user', $e->getMessage(), $e->getCode());
        }
    }
}
