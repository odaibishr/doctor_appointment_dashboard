<?php

namespace App\Filament\Resources\DoctorWaitlists;

use App\Filament\Resources\DoctorWaitlists\Pages\CreateDoctorWaitlist;
use App\Filament\Resources\DoctorWaitlists\Pages\EditDoctorWaitlist;
use App\Filament\Resources\DoctorWaitlists\Pages\ListDoctorWaitlists;
use App\Filament\Resources\DoctorWaitlists\Schemas\DoctorWaitlistForm;
use App\Filament\Resources\DoctorWaitlists\Tables\DoctorWaitlistTable;
use App\Models\DoctorWaitlist;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DoctorWaitlistResource extends Resource
{
    protected static ?string $model = DoctorWaitlist::class;

    protected static ?string $modelLabel = 'قائمة انتظار';

    protected static ?string $pluralModelLabel = 'قوائم الانتظار';

    protected static ?string $navigationLabel = 'قوائم الانتظار';

    protected static UnitEnum|string|null $navigationGroup = 'إدارة المواعيد';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return DoctorWaitlistForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DoctorWaitlistTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'waiting')->count();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDoctorWaitlists::route('/'),
            'create' => CreateDoctorWaitlist::route('/create'),
            'edit' => EditDoctorWaitlist::route('/{record}/edit'),
        ];
    }
}
