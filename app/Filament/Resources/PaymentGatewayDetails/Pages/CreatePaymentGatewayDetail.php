<?php

namespace App\Filament\Resources\PaymentGatewayDetails\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\PaymentGatewayDetails\PaymentGatewayDetailResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentGatewayDetail extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = PaymentGatewayDetailResource::class;
}

