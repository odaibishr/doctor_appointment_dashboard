<?php

namespace App\Filament\Resources\Days;

use App\Filament\Resources\Days\Pages\CreateDay;
use App\Filament\Resources\Days\Pages\EditDay;
use App\Filament\Resources\Days\Pages\ListDays;
use App\Filament\Resources\Days\Schemas\DayForm;
use App\Filament\Resources\Days\Tables\DaysTable;
use App\Models\Day;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DayResource extends Resource
{
    protected static ?string $model = Day::class;

    protected static ?string $modelLabel = 'يوم';

    protected static ?string $pluralModelLabel = 'الأيام';

    protected static ?string $navigationLabel = 'الأيام';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'day_name';

    public static function form(Schema $schema): Schema
    {
        return DayForm::configure($schema);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->isAdmin() ?? false;
    }

    public static function table(Table $table): Table
    {
        return DaysTable::configure($table);
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
            'index' => ListDays::route('/'),
            'create' => CreateDay::route('/create'),
            'edit' => EditDay::route('/{record}/edit'),
        ];
    }
}
