<?php

namespace App\Filament\Resources\PaymentGatewayDetails\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PaymentGatewayDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('gateway_name')
                    ->label('اسم بوابة الدفع')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('api_key')
                    ->label('مفتاح API')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('api_secret')
                    ->label('سر API')
                    ->required()
                    ->columnSpan(2),

                Toggle::make('is_active')
                    ->label('مفعل')
                    ->columnSpan(1),

                FileUpload::make('logo')
                    ->label('الشعار')
                    ->image()
                    ->imagePreviewHeight(100)
                    ->imageResizeTargetHeight(100)
                    ->imageResizeTargetWidth(100)
                    ->columnSpan(2),
            ]);
    }
}
