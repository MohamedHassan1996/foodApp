<?php

namespace App\Http\Controllers\Api\Public\Auth;

use App\Enums\StatusCode\StatusCode;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\UserAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(UserAuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    /*
    ** register method
    **
    **
    */
    public function register(RegisterRequest $registerReq)
    {
        return $this->authService->register($registerReq->validated());
    }


    /*
    ** login method
    **
    **
    */
    public function login(LoginRequest $loginReq)
    {

        try {


            $token = $this->authService->login($loginReq->validated());

            if(!$token['success']){

                return ResponseHelper::error($token['message'], StatusCode::UNAUTHORIZED);
            }


            return ResponseHelper::success($token, 'userAuth.success_login');


        } catch (\Throwable $th) {
            return ResponseHelper::error($th->getMessage(), StatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    /*
    ** logout method
    **
    **
    */

    public function logout()
    {
        try {
            $logout = $this->authService->logout();

            return ResponseHelper::success($logout, 'تم تسجيل الخروج بنجاح');
        }catch(\Throwable $th){
            return ResponseHelper::error($th->getMessage(), StatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
