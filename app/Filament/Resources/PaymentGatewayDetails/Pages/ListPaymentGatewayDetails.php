<?php

namespace App\Filament\Resources\PaymentGatewayDetails\Pages;

use App\Filament\Resources\PaymentGatewayDetails\PaymentGatewayDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentGatewayDetails extends ListRecords
{
    protected static string $resource = PaymentGatewayDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }
}
