<?php

namespace App\Filament\Resources\FavoriteDoctors;

use App\Filament\Resources\FavoriteDoctors\Pages\CreateFavoriteDoctor;
use App\Filament\Resources\FavoriteDoctors\Pages\EditFavoriteDoctor;
use App\Filament\Resources\FavoriteDoctors\Pages\ListFavoriteDoctors;
use App\Filament\Resources\FavoriteDoctors\Schemas\FavoriteDoctorForm;
use App\Filament\Resources\FavoriteDoctors\Tables\FavoriteDoctorsTable;
use App\Models\FavoriteDoctor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FavoriteDoctorResource extends Resource
{
    protected static ?string $model = FavoriteDoctor::class;

    protected static ?string $modelLabel = 'طبيب مفضل';

    protected static ?string $pluralModelLabel = 'الأطباء المفضلون';

    protected static ?string $navigationLabel = 'الأطباء المفضلون';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return FavoriteDoctorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FavoriteDoctorsTable::configure($table);
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
            'index' => ListFavoriteDoctors::route('/'),
            'create' => CreateFavoriteDoctor::route('/create'),
            'edit' => EditFavoriteDoctor::route('/{record}/edit'),
        ];
    }
}
