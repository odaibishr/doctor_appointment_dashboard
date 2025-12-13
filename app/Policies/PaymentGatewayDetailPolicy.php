<?php

namespace App\Policies;

use App\Models\PaymentGatewayDetail;
use App\Models\User;

class PaymentGatewayDetailPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, PaymentGatewayDetail $paymentGatewayDetail): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, PaymentGatewayDetail $paymentGatewayDetail): bool
    {
        return false;
    }

    public function delete(User $user, PaymentGatewayDetail $paymentGatewayDetail): bool
    {
        return false;
    }
}

