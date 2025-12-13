<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Location;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $location = Location::query()->firstOrCreate(
            ['name' => 'Default'],
            ['lat' => '0', 'lng' => '0'],
        );

        $specialty = Specialty::query()->firstOrCreate(
            ['name' => 'General'],
            ['icon' => null, 'is_active' => true],
        );

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ],
        );

        $doctorUser = User::query()->firstOrCreate(
            ['email' => 'doctor@example.com'],
            [
                'name' => 'Doctor User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_DOCTOR,
            ],
        );

        Doctor::query()->firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'name' => 'Doctor Profile',
                'email' => 'doctor.profile@example.com',
                'phone' => '0000000000',
                'location_id' => $location->id,
                'specialty_id' => $specialty->id,
                'gender' => 'Male',
                'password' => Hash::make('password'),
            ],
        );

        $patientUser = User::query()->firstOrCreate(
            ['email' => 'patient@example.com'],
            [
                'name' => 'Patient User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PATIENT,
            ],
        );

        Patient::query()->firstOrCreate(
            ['user_id' => $patientUser->id],
            [
                'phone' => '0000000000',
                'gender' => 'male',
                'location_id' => $location->id,
            ],
        );
    }
}
