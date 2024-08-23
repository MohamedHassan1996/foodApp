<?php

namespace App\Services\Auth;

interface VerifyEmailServiceInterface {
    public function verify(array $data);
}
