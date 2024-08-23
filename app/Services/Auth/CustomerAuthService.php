<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use App\Models\Customer\Customer;
use App\Models\VerificationCode\VerificationCode;

class CustomerAuthService implements AuthServiceInterface, VerifyEmailServiceInterface
{

    public function __construct(
    )
    {
    }

    public function register(array $data){

        $customer = Customer::create([
            'name'=> $data['name'],
            'email'=> $data['email'],
            'password'=> $data['password'],
            'status' => 0,
            'avatar' => null
        ]);

        return $customer;

    }

    public function login(array $credentials)
    {
        $customerToken = Auth::guard('customer')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']]);

        // Validate user login
        $validationResult = $this->validateCustomerLogin($customerToken);


        if (!$validationResult['success']) {
            return $validationResult;
        }

        $customer = Auth::guard('customer')->user();

        return [
            'token' => $customerToken,
        ];
    }

    public function verify(array $data)
    {

        $customer = Customer::where('email', $data['email'])->first();

        if (!$customer) {
            return [
                'success' => false,
                'message' => 'customerAuth.invalid_account',
            ];
        }

        $verificationCode = VerificationCode::isValid($customer->id, $data['code']);

        if (!$verificationCode) {
            return [
                'success' => false,
                'message' => 'activationCode.invalid_code',
            ];
        }

        $customer->verifyEmail();

        // Optionally, delete the verification code after successful verification
        $verificationCode->delete();

        return [
            'success' => true,
        ];
    }


    public function logout()
    {
        return Auth::guard('customers')->logout() ? true : false;
    }

    protected function validateCustomerLogin($userToken)
    {

        if (!$userToken) {
            return [
                'success' => false,
                'message' => 'customerAuth.data_error',
            ];
        }

        if (Auth::guard('customer')->user()->status->value == 0) {
            return [
                'success' => false,
                'message' => 'customerAuth.inactive_account',
            ];
        }


        return ['success' => true];
    }


}
