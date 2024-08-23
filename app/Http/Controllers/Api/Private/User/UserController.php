<?php

namespace App\Http\Controllers\Api\Private\User;

use App\Enums\StatusCode\StatusCode;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\AllUserDataResource;
use App\Http\Resources\User\AllUserCollection;
use App\Utils\PaginateCollection;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:all_users', ['only' => ['allUsers']]);
        $this->middleware('permission:create_user', ['only' => ['create']]);
        $this->middleware('permission:edit_user', ['only' => ['edit']]);
        $this->middleware('permission:update_user', ['only' => ['update']]);
        $this->middleware('permission:delete_user', ['only' => ['delete']]);
        $this->middleware('permission:change_user_status', ['only' => ['changeStatus']]);
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function allUsers(Request $request)
    {
        $allUsers = $this->userService->allUsers();

        return response()->json(
            new AllUserCollection(PaginateCollection::paginate($allUsers, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateUserRequest $createUserRequest)
    {

        try {
            DB::beginTransaction();

            $this->userService->createUser($createUserRequest->validated());

            DB::commit();

            return ResponseHelper::success([], 'user.created_success');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {
        $user  =  $this->userService->editUser($request->userId);

        return ResponseHelper::success(new AllUserDataResource($user));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $updateUserRequest)
    {

        try {
            DB::beginTransaction();
            $this->userService->updateUser($updateUserRequest->validated());
            DB::commit();
            return ResponseHelper::success([], 'user.updated_success');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->userService->deleteUser($request->userId);
            DB::commit();
            return ResponseHelper::success([], 'user.deleted_success');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    public function changeStatus(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->userService->changeUserStatus($request->userId, $request->status);
            DB::commit();

            return ResponseHelper::success([], 'user.status_changed');


        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::error($e->getMessage(), StatusCode::INTERNAL_SERVER_ERROR);
        }

    }

}
