<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;
class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gender::create([
            "name" => "Male"
        ]);
        Gender::create([
            "name" => "Female"
        ]);
        Gender::create([
            "name" => "Non-Binary"
        ]);
    }
}
