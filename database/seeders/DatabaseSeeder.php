<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\RoomList;
use Illuminate\Database\Seeder;
use Database\Seeders\CategoryList;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            CategoryList::class,
            RoomList::class,
            MetalList::class,
            CountryCurrencySeeder::class,
        ]);
    }
}
