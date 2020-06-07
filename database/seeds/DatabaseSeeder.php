<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(LicenseSeeder::class);
        $this->call(EmailCampaignSeeder::class);
        $this->call(ExternalAdSeeder::class);
        $this->call(AnalyticsTableSeeder::class);
    }
}
