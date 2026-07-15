<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = date('Y');

        $holidays = [
            [
                'name' => "New Year's Day",
                'start_date' => "$currentYear-01-01",
                'end_date' => "$currentYear-01-01",
                'type' => 'public',
                'description' => 'Celebration of the New Year.',
                'status' => 'active',
            ],
            [
                'name' => 'Republic Day',
                'start_date' => "$currentYear-01-26",
                'end_date' => "$currentYear-01-26",
                'type' => 'public',
                'description' => 'Republic Day of India.',
                'status' => 'active',
            ],
            [
                'name' => 'Independence Day',
                'start_date' => "$currentYear-08-15",
                'end_date' => "$currentYear-08-15",
                'type' => 'public',
                'description' => 'Independence Day of India.',
                'status' => 'active',
            ],
            [
                'name' => 'Gandhi Jayanti',
                'start_date' => "$currentYear-10-02",
                'end_date' => "$currentYear-10-02",
                'type' => 'public',
                'description' => 'Birth anniversary of Mahatma Gandhi.',
                'status' => 'active',
            ],
            [
                'name' => 'Christmas Day',
                'start_date' => "$currentYear-12-25",
                'end_date' => "$currentYear-12-25",
                'type' => 'public',
                'description' => 'Christmas celebration.',
                'status' => 'active',
            ],
            [
                'name' => 'Holi',
                'start_date' => "$currentYear-03-14",
                'end_date' => "$currentYear-03-14",
                'type' => 'public',
                'description' => 'Festival of Colours.',
                'status' => 'active',
            ],
            [
                'name' => 'Good Friday',
                'start_date' => "$currentYear-04-18",
                'end_date' => "$currentYear-04-18",
                'type' => 'public',
                'description' => 'Christian religious observance.',
                'status' => 'active',
            ],
            [
                'name' => 'Eid-ul-Fitr',
                'start_date' => "$currentYear-03-31",
                'end_date' => "$currentYear-03-31",
                'type' => 'public',
                'description' => 'Festival marking the end of Ramadan.',
                'status' => 'active',
            ],
            [
                'name' => 'Eid-ul-Adha',
                'start_date' => "$currentYear-06-07",
                'end_date' => "$currentYear-06-07",
                'type' => 'public',
                'description' => 'Festival of Sacrifice.',
                'status' => 'active',
            ],
            [
                'name' => 'Diwali',
                'start_date' => "$currentYear-10-20",
                'end_date' => "$currentYear-10-20",
                'type' => 'public',
                'description' => 'Festival of Lights.',
                'status' => 'active',
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::firstOrCreate(
                ['name' => $holiday['name'], 'start_date' => $holiday['start_date']],
                $holiday
            );
        }
    }
}
