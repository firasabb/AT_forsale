<?php

use Illuminate\Database\Seeder;
use \App\Analytic;

class AnalyticsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Analytic::create(['name' => 'views']);
        Analytic::create(['name' => 'downloads']);
    }
}
