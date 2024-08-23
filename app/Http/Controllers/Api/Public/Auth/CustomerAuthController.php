<?php

namespace App\Http\Controllers\Api\Public\Auth;

use App\Enums\StatusCode\StatusCode;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CustomerLoginRequest;
use App\Http\Requests\Auth\CustomerVerifyRequest;
use App\Http\Requests\Customer\CustomerRegisterRequest;
use App\Mail\VerificationCodeMail;
use App\Models\VerificationCode\VerificationCode;
use App\Services\Auth\CustomerAuthService;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CustomerAuthController extends Controller
{
    protected $customerAuthService;
    public function __construct(CustomerAuthService $customerAuthService)
    {
        //$this->middleware('auth:customer');
        $this->customerAuthService = $customerAuthService;
    }

    public function register(CustomerRegisterRequest $customerRegisterRequest)
    {

        try {

            $customer = $this->customerAuthService->register($customerRegisterRequest->validated());

            $verificationCode = VerificationCode::create([
                'customer_id' => $customer->id,
            ]);

            $content = [
                'body' => $verificationCode->code
            ];

            Mail::to($customer->email)->send(new VerificationCodeMail($content));

            return ResponseHelper::success([], 'customerAuth.success_register');

        } catch (Throwable $th) {
            return ResponseHelper::error($th->getMessage(), StatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function verify(CustomerVerifyRequest $request)
    {
        try{
            $verifiedCustomer = $this->customerAuthService->verify($request->validated());

            if($verifiedCustomer['success']){

                return ResponseHelper::success([], 'customerAuth.success_verify');
            }

            return ResponseHelper::error($verifiedCustomer['message'], StatusCode::UNAUTHORIZED);

        }catch(Throwable $th){

            return ResponseHelper::error($th->getMessage(), StatusCode::INTERNAL_SERVER_ERROR);
        }

    }

    public function login(CustomerLoginRequest $customerLoginRequest)
    {

        try {
            $customerToken = $this->customerAuthService->login($customerLoginRequest->validated());

            if(isset($customerToken['success']) && !$customerToken['success']){

                return ResponseHelper::error($customerToken['message'], StatusCode::UNAUTHORIZED);

            }

            return ResponseHelper::success($customerToken, 'customerAuth.success_login');


        }catch(Throwable $th){

            return ResponseHelper::error($th->getMessage(), StatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            $logout = $this->customerAuthService->logout();

            return ResponseHelper::success($logout, 'تم تسجيل الخروج بنجاح');
        }catch(Throwable $th){
            return ResponseHelper::error($th->getMessage(), StatusCode::INTERNAL_SERVER_ERROR);
        }
    }

}
