<?php

use Illuminate\Database\Seeder;
use App\ExternalAd;

class ExternalAdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExternalAd::create(['name' => 'download',
        'body' => '<img src="https://elasticbeanstalk-us-east-2-086418636769.s3.us-east-2.amazonaws.com/featured/default/default.jpg" alt="Woooooooooooooooooooooooooooooow" class="card-body-img">']);
    }
}
