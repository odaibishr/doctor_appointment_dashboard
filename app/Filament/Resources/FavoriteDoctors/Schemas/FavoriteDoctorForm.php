<?php

namespace App\Filament\Resources\FavoriteDoctors\Schemas;

use App\Models\Doctor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class FavoriteDoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctor_id')
                    ->label('الطبيب')
                    ->options(fn (): array => Doctor::query()
                        ->join('users', 'users.id', '=', 'doctors.user_id')
                        ->orderBy('users.name')
                        ->pluck('users.name', 'doctors.id')
                        ->all())
                    ->searchable()
                    ->required(),

                Select::make('user_id')
                    ->label('المريض')
                    ->relationship('user', 'name')
                    ->required()
                    ->default(fn () => Auth::id())
                    ->disabled(fn () => ! Auth::user()?->isAdmin())
                    ->hidden(fn () => ! Auth::user()?->isAdmin()),
            ]);
    }
}
