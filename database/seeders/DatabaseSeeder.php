<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Location;
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
        $this->call(DaysSeeder::class);

        $location = Location::query()->firstOrCreate(
            ['name' => 'Default'],
            ['lat' => '0', 'lng' => '0'],
        );

        $specialty = Specialty::query()->firstOrCreate(
            ['name' => 'General'],
            ['icon' => null, 'is_active' => true],
        );

        $admin = User::query()->firstOrCreate(
            ['email' => 'odaibishr@gmail.com'],
            [
                'name' => 'عدي بشر',
                'password' => Hash::make('odaibishr'),
                'role' => User::ROLE_ADMIN,
                'phone' => '0000000000',
                'gender' => 'male',
                'location_id' => $location->id,
            ],
        );

        $doctorUser = User::query()->firstOrCreate(
            ['email' => 'doctor@example.com'],
            [
                'name' => 'Doctor User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_DOCTOR,
                'phone' => '0000000000',
                'gender' => 'Male',
                'location_id' => $location->id,
            ],
        );

        Doctor::query()->firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'specialty_id' => $specialty->id,
            ],
        );

        $patientUser = User::query()->firstOrCreate(
            ['email' => 'patient@example.com'],
            [
                'name' => 'Patient User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PATIENT,
                'phone' => '0000000000',
                'gender' => 'male',
                'location_id' => $location->id,
            ],
        );
    }
}
