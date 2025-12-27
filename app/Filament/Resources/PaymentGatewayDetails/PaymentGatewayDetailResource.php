<?php

namespace App\Filament\Resources\PaymentGatewayDetails;

use App\Filament\Resources\PaymentGatewayDetails\Pages\CreatePaymentGatewayDetail;
use App\Filament\Resources\PaymentGatewayDetails\Pages\EditPaymentGatewayDetail;
use App\Filament\Resources\PaymentGatewayDetails\Pages\ListPaymentGatewayDetails;
use App\Filament\Resources\PaymentGatewayDetails\Schemas\PaymentGatewayDetailForm;
use App\Filament\Resources\PaymentGatewayDetails\Tables\PaymentGatewayDetailsTable;
use App\Models\PaymentGatewayDetail;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentGatewayDetailResource extends Resource
{
    protected static ?string $model = PaymentGatewayDetail::class;

    protected static ?string $modelLabel = 'بوابة دفع';

    protected static ?string $pluralModelLabel = 'بوابات الدفع';

    protected static ?string $navigationLabel = 'بوابات الدفع';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'gateway_name';

    public static function form(Schema $schema): Schema
    {
        return PaymentGatewayDetailForm::configure($schema);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->isAdmin() ?? false;
    }

    public static function table(Table $table): Table
    {
        return PaymentGatewayDetailsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentGatewayDetails::route('/'),
            'create' => CreatePaymentGatewayDetail::route('/create'),
            'edit' => EditPaymentGatewayDetail::route('/{record}/edit'),
        ];
    }
}
