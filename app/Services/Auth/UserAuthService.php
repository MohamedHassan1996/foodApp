<?php

namespace App\Services\Auth;

use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\UserRolePremission\UserPermissionService;
use Spatie\Permission\Models\Role;

class UserAuthService
{

    protected $userPermissionService;

    public function __construct(
        UserPermissionService $userPermissionService,
    )
    {
        $this->userPermissionService = $userPermissionService;
    }

    public function register(array $data){
        try {

            $user = User::create([
                'name'=> $data['name'],
                'surname'=> $data['surname'],
                'email'=> $data['email'],
                'password'=> Hash::make($data['password']),
            ]);

            return response()->json([
                'message' => 'user has been created!'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

    }


    public function login(array $data)
    {

        $userToken = Auth::attempt(['username' => $data['username'], 'password' => $data['password']]);

        if(!$userToken){
            return [
                'success' => false,
                'message' => 'userAuth.data_error',
            ];
        }

        if($userToken && Auth::user()->status->value == 0){
            return [
                'success' => false,
                'message' => 'userAuth.inactive_account',
            ];
        }


        $user = Auth::user();
        $userRoles = $user->getRoleNames();
        $role = Role::findByName($userRoles[0]);
        $roleWithPermissions = $role->permissions;

        return [
            'token' => $userToken,
            'profile' => new UserResource($user),
            'role' => new RoleResource($role),
            'permissions' => $this->userPermissionService->getUserPermissions($user),
        ];

    }

    public function logout()
    {
        Auth::logout();

    }

}
