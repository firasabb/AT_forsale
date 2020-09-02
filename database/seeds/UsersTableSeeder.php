<?php

use Illuminate\Database\Seeder;
use \App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'First',
            'last_name' => 'Last',
            'username' => 'example',
            'email' => 'example@example.com',
            'email_verified_at' => Carbon\Carbon::now(),
            'password' => bcrypt('123123qweqwe')
        ]);

        $user->assignRole(['user', 'moderator', 'admin']);
    }
}
