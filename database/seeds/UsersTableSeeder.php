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
        $user = new User();
        $user->first_name = 'Phil';
        $user->last_name = 'Anselmo';
        $user->email = 'example@example.com';
        $user->password = bcrypt('123123qweqwe');
        $user->username = 'freezabb';
        $user->bio = 'A musician, guitarist and app developer.';
        $user->save();

        $user->assignRole('admin', 'moderator', 'user');
    }
}
