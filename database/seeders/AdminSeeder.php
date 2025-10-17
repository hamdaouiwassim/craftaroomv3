<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => "admin@admin.com",
            'password' => bcrypt('password'),
            'role' => 0,
            
        ]);
    }
}
