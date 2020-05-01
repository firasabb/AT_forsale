<?php

use Illuminate\Database\Seeder;
use App\License;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $license = License::create([
            'name' => 'CC0 1.0 Universal',
            'url' => 'cc0-universal',
            'link' => 'https://creativecommons.org/publicdomain/zero/1.0/'
        ]);
    }
}
