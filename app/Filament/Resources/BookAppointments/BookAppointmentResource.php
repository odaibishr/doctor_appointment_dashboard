<?php

namespace App\Filament\Resources\BookAppointments;

use App\Filament\Resources\BookAppointments\Pages\CreateBookAppointment;
use App\Filament\Resources\BookAppointments\Pages\EditBookAppointment;
use App\Filament\Resources\BookAppointments\Pages\ListBookAppointments;
use App\Filament\Resources\BookAppointments\Schemas\BookAppointmentForm;
use App\Filament\Resources\BookAppointments\Tables\BookAppointmentsTable;
use App\Models\BookAppointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookAppointmentResource extends Resource
{
    protected static ?string $model = BookAppointment::class;

    protected static ?string $modelLabel = 'حجز موعد';

    protected static ?string $pluralModelLabel = 'حجوزات المواعيد';

    protected static ?string $navigationLabel = 'حجوزات المواعيد';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'date';

    public static function form(Schema $schema): Schema
    {
        return BookAppointmentForm::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if (! $user) {
            return $query;
        }

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isDoctor()) {
            $doctorId = $user->doctor?->id;

            return $doctorId ? $query->where('doctor_id', $doctorId) : $query->whereRaw('1 = 0');
        }

        if ($user->isPatient()) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public static function table(Table $table): Table
    {
        return BookAppointmentsTable::configure($table);
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
            'index' => ListBookAppointments::route('/'),
            'create' => CreateBookAppointment::route('/create'),
            'edit' => EditBookAppointment::route('/{record}/edit'),
        ];
    }
}
