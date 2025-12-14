<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;

class DaysSeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            ['day_number' => 1, 'day_name' => 'السبت', 'short_name' => 'سبت'],
            ['day_number' => 2, 'day_name' => 'الأحد', 'short_name' => 'أحد'],
            ['day_number' => 3, 'day_name' => 'الإثنين', 'short_name' => 'اثن'],
            ['day_number' => 4, 'day_name' => 'الثلاثاء', 'short_name' => 'ثلا'],
            ['day_number' => 5, 'day_name' => 'الأربعاء', 'short_name' => 'أرب'],
            ['day_number' => 6, 'day_name' => 'الخميس', 'short_name' => 'خمي'],
            ['day_number' => 7, 'day_name' => 'الجمعة', 'short_name' => 'جمع'],
        ];

        foreach ($days as $day) {
            Day::query()->updateOrCreate(
                ['day_number' => $day['day_number']],
                ['day_name' => $day['day_name'], 'short_name' => $day['short_name']],
            );
        }
    }
}
