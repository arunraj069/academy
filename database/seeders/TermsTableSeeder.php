<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Term;
use Carbon\Carbon;
class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Term::create([
            "name" => "First",
            "start"=>Carbon::now()->toDateTimeString(),
            "end"=>Carbon::now()->addMonths(3)->endOfMonth()->toDateTimeString()
        ]);
        Term::create([
            "name" => "Second",
            "start"=>Carbon::now()->addMonths(4)->toDateTimeString(),
            "end"=>Carbon::now()->addMonths(7)->endOfMonth()->toDateTimeString()
        ]);
        Term::create([
            "name" => "Third",
            "start"=>Carbon::now()->addMonths(8)->toDateTimeString(),
            "end"=>Carbon::now()->addMonths(12)->endOfMonth()->toDateTimeString()
        ]);
    }
}
