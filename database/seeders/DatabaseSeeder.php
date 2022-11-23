<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\HolidaySeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $holidaySeeder;

    public function __construct(HolidaySeeder $holidaySeeder, )
    {
        $this->HolidaySeeder = $holidaySeeder;
    }
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->HolidaySeeder->run();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
