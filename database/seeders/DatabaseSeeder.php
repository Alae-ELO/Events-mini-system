<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Place;
use App\Models\Category;
use App\Models\Event;
use App\Models\Booking;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();
        Place::factory(1)->create();
        Category::factory(1)->create();
        Event::factory(1)->create();
        Booking::factory(1)->create();
    }
}
