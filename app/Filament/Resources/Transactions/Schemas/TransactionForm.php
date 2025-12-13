<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\PaymentGatewayDetail;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('amount')
                    ->label('المبلغ')
                    ->required()
                    ->numeric()
                    ->default(0),

                Select::make('payment_gateway_detail_id')
                    ->label('بوابة الدفع')
                    ->relationship('paymentGatewayDetail', 'gateway_name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('gateway_name')
                            ->label('اسم بوابة الدفع')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('api_key')
                            ->label('مفتاح API')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('api_secret')
                            ->label('سر API')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('مفعل')
                            ->default(true),
                        FileUpload::make('logo')
                            ->label('الشعار')
                            ->image()
                            ->disk('public')
                            ->directory('payment-gateways'),
                    ])
                    ->createOptionAction(fn ($action) => $action->modalHeading('إضافة بوابة دفع')->modalSubmitActionLabel('حفظ'))
                    ->createOptionUsing(function (array $data): int {
                        $gatewayName = trim((string) ($data['gateway_name'] ?? ''));

                        $existing = PaymentGatewayDetail::query()
                            ->when($gatewayName !== '', fn ($q) => $q->where('gateway_name', $gatewayName))
                            ->first();

                        if ($existing) {
                            $existing->fill($data)->save();

                            return (int) $existing->getKey();
                        }

                        return (int) PaymentGatewayDetail::query()->create($data)->getKey();
                    })
                    ->required(),
            ]);
    }
}
