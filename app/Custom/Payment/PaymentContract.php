<?php

namespace App\Custom\Payment;

interface PaymentContract
{
    public function verify($token, $amount = null, $model = null);

    public function hasError();

    public function getErrorMessage();

    public function isVerified();

    public function getResponse();
}
