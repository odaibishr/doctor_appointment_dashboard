<?php

namespace App\Filament\Resources\DoctorDaysOffs;

use App\Filament\Resources\DoctorDaysOffs\Pages\CreateDoctorDaysOff;
use App\Filament\Resources\DoctorDaysOffs\Pages\EditDoctorDaysOff;
use App\Filament\Resources\DoctorDaysOffs\Pages\ListDoctorDaysOffs;
use App\Filament\Resources\DoctorDaysOffs\Schemas\DoctorDaysOffForm;
use App\Filament\Resources\DoctorDaysOffs\Tables\DoctorDaysOffsTable;
use App\Models\DoctorDaysOffs;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DoctorDaysOffResource extends Resource
{
    protected static ?string $model = DoctorDaysOffs::class;

    protected static ?string $modelLabel = 'إجازة طبيب';

    protected static ?string $pluralModelLabel = 'أيام الإجازة للأطباء';

    protected static ?string $navigationLabel = 'أيام الإجازة للأطباء';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return DoctorDaysOffForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DoctorDaysOffsTable::configure($table);
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
            'index' => ListDoctorDaysOffs::route('/'),
            'create' => CreateDoctorDaysOff::route('/create'),
            'edit' => EditDoctorDaysOff::route('/{record}/edit'),
        ];
    }
}
