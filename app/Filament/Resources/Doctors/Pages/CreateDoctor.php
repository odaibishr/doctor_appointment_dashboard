<?php

namespace App\Filament\Resources\Doctors\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Doctors\DoctorResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CreateDoctor extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = DoctorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $currentUser = auth()->user();
        if (! $currentUser?->isAdmin()) {
            return $data;
        }

        $email = (string) ($data['email'] ?? '');
        $password = (string) ($data['password'] ?? '');

        if ($password === '') {
            throw ValidationException::withMessages([
                'password' => 'كلمة المرور مطلوبة.',
            ]);
        }

        if ($email === '') {
            throw ValidationException::withMessages([
                'email' => 'البريد الإلكتروني مطلوب لإنشاء حساب الطبيب.',
            ]);
        }

        if (User::query()->where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'البريد الإلكتروني مستخدم مسبقًا.',
            ]);
        }

        $user = User::query()->create([
            'name' => (string) ($data['name'] ?? 'Doctor'),
            'email' => $email,
            'password' => Hash::make($password),
            'role' => User::ROLE_DOCTOR,
            'phone' => (string) ($data['phone'] ?? ''),
            'gender' => (string) ($data['gender'] ?? ''),
            'location_id' => $data['location_id'] ?? null,
            'profile_image' => $data['profile_image'] ?? null,
        ]);

        $data['user_id'] = $user->id;

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
