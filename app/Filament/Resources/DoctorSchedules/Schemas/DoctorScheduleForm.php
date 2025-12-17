<?php

namespace App\Filament\Resources\DoctorSchedules\Schemas;

use App\Models\Doctor;
use App\Models\DoctorDaysOff;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleForm
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
                    ->required()
                    ->default(fn () => Auth::user()?->doctor?->id)
                    ->disabled(fn () => ! Auth::user()?->isAdmin())
                    ->hidden(fn () => ! Auth::user()?->isAdmin())
                    ->columnSpan(2),

                Select::make('day_id')
                    ->label('اليوم')
                    ->relationship(
                        'day',
                        'day_name',
                        modifyQueryUsing: function (Builder $query): Builder {
                            $user = Auth::user();

                            if (! $user?->isDoctor()) {
                                return $query;
                            }

                            $doctorId = $user->doctor?->id;

                            if (! $doctorId) {
                                return $query->whereRaw('1 = 0');
                            }

                            $daysOff = DoctorDaysOff::query()
                                ->where('doctor_id', $doctorId)
                                ->pluck('day_id')
                                ->all();

                            return $daysOff !== [] ? $query->whereNotIn('id', $daysOff) : $query;
                        },
                    )
                    ->required()
                    ->columnSpan(2),

                TimePicker::make('start_time')
                    ->label('بداية الدوام')
                    ->required()
                    ->columnSpan(1),

                TimePicker::make('end_time')
                    ->label('نهاية الدوام')
                    ->required()
                    ->columnSpan(1),
            ]);
    }
}
