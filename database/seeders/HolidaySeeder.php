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
                'name' => 'New Year\'s Day',
                'start_date' => "$currentYear-01-01",
                'end_date' => "$currentYear-01-01",
                'type' => 'public',
                'description' => 'Celebration of the start of the New Year.',
                'status' => 'active',
            ],
            [
                'name' => 'Republic Day',
                'start_date' => "$currentYear-01-26",
                'end_date' => "$currentYear-01-26",
                'type' => 'public',
                'description' => 'Honors the date on which the Constitution of India came into effect.',
                'status' => 'active',
            ],
            [
                'name' => 'Holi Festival',
                'start_date' => "$currentYear-03-14",
                'end_date' => "$currentYear-03-15",
                'type' => 'public',
                'description' => 'Festival of colors and spring celebration.',
                'status' => 'active',
            ],
            [
                'name' => 'Company Foundation Day',
                'start_date' => "$currentYear-05-10",
                'end_date' => "$currentYear-05-10",
                'type' => 'company',
                'description' => 'Annual holiday celebrating the foundation of StaffHub.',
                'status' => 'active',
            ],
            [
                'name' => 'Independence Day',
                'start_date' => "$currentYear-08-15",
                'end_date' => "$currentYear-08-15",
                'type' => 'public',
                'description' => 'Commemorates the nation\'s independence.',
                'status' => 'active',
            ],
            [
                'name' => 'Gandhi Jayanti',
                'start_date' => "$currentYear-10-02",
                'end_date' => "$currentYear-10-02",
                'type' => 'public',
                'description' => 'Birthday of Mahatma Gandhi.',
                'status' => 'active',
            ],
            [
                'name' => 'Diwali Festival',
                'start_date' => "$currentYear-11-08",
                'end_date' => "$currentYear-11-10",
                'type' => 'public',
                'description' => 'Festival of lights celebration.',
                'status' => 'active',
            ],
            [
                'name' => 'Christmas Day',
                'start_date' => "$currentYear-12-25",
                'end_date' => "$currentYear-12-25",
                'type' => 'public',
                'description' => 'Celebration of Christmas.',
                'status' => 'active',
            ],
            [
                'name' => 'Employee Birthday (Optional)',
                'start_date' => "$currentYear-06-15",
                'end_date' => "$currentYear-06-15",
                'type' => 'optional',
                'description' => 'Optional holiday for employees to celebrate their birthdays.',
                'status' => 'active',
            ]
        ];

        foreach ($holidays as $holiday) {
            Holiday::firstOrCreate(
                ['name' => $holiday['name'], 'start_date' => $holiday['start_date']],
                $holiday
            );
        }
    }
}
