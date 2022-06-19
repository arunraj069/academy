<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
                "name" => "Admin",
                "email" => "admin@gmail.com",
                "password" => bcrypt("admin@123")
            ]);
    }
}
