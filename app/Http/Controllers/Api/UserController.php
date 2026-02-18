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

            return $this->success(Lang::get('user.success_fetch_all_users'), UserResource::collection($users));
        } catch (\Exception $e) {
            return $this->error(Lang::get('user.failed_fetch_all_users'), $e->getMessage(), $e->getCode());
        }
    }

    public function detail(string $id)
    {
        try {
            $user = $this->userService->getById($id);

            return $this->success(Lang::get('user.success_fetch_user'), new UserResource($user));
        } catch (\Exception $e) {
            return $this->error(Lang::get('user.failed_fetch_user'), $e->getMessage(), $e->getCode());
        }
    }

    public function update(UserRequest $request, string $id)
    {
        try {
            $user = $this->userService->update($request->all(), $id);

            return $this->success(Lang::get('user.success_update_user'), new UserResource($user));
        } catch (\Exception $e) {
            return $this->error(Lang::get('user.failed_update_user'), $e->getMessage(), $e->getCode());
        }
    }

    public function delete(string $id)
    {
        try {
            $this->userService->delete($id);
            
            return $this->success(Lang::get('user.success_delete_user'));
        } catch (\Exception $e) {
            return $this->error(Lang::get('user.failed_delete_user'), $e->getMessage(), $e->getCode());
        }
    }
}
