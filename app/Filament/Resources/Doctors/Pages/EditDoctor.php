<?php

namespace App\Filament\Resources\Doctors\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Doctors\DoctorResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EditDoctor extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = DoctorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => static::getResource()::canDelete($this->getRecord())),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $doctor = $this->getRecord();
        $user = $doctor->user;

        if ($user) {
            $email = array_key_exists('email', $data) ? (string) $data['email'] : null;

            if ($email !== null && $email !== '' && User::query()->where('email', $email)->whereKeyNot($user->id)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'البريد الإلكتروني مستخدم مسبقًا.',
                ]);
            }

            $password = array_key_exists('password', $data) ? (string) $data['password'] : '';

            $user->fill(array_filter([
                'name' => $data['name'] ?? null,
                'email' => $email,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? null,
                'location_id' => $data['location_id'] ?? null,
                'profile_image' => $data['profile_image'] ?? null,
            ], fn ($v) => $v !== null));

            if ($password !== '') {
                $user->password = Hash::make($password);
            }

            $user->save();
        }

        unset(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['phone'],
            $data['gender'],
            $data['location_id'],
            $data['profile_image'],
        );

        return $data;
    }
}
