<?php

use Illuminate\Database\Seeder;
use App\EmailCampaign;

class EmailCampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailCampaign::create(['name' => 'news']);
        EmailCampaign::create(['name' => 'offers']);
        EmailCampaign::create(['name' => 'notifications']);
    }
}
