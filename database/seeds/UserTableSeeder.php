<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $users = [
            [
                'name' => 'John Smith',
                'username' => 'johnsmith',
                'email' => 'john@test.com',
                'password' => Hash::make( 'password' ),
                'type' => 1
            ],
            [
                'name' => 'Laura Stone',
                'username' => 'laurastone',
                'email' => 'laura@test.com',
                'password' => Hash::make( 'password' ),
                'type' => 0
            ],
            [
                'name' => 'Jon Oisson',
                'username' => 'jonoisson',
                'email' => 'jon@test.com',
                'password' => Hash::make( 'password' ),
                'type' => 0
            ]
        ];

        $db = DB::table('users')->insert($users);
    }
}
