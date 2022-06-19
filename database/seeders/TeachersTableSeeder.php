<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Teacher::create([
                "name" => "Mark",
                "email" => "mark@gmail.com",
                "password" => bcrypt("mark@123")
            ]);
       	Teacher::create([
                "name" => "John",
                "email" => "john@gmail.com",
                "password" => bcrypt("john@123")
            ]);
       	Teacher::create([
                "name" => "Juliet",
                "email" => "juliet@gmail.com",
                "password" => bcrypt("juliet@123")
            ]);
    }
}
