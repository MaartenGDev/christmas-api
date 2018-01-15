<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(collect(explode(',', env('DEFAULT_USERS')))->map(function ($user) {
            list($name, $password) = explode(':', $user);

            return [
                'name' => $name,
                'email' => $name . '@example.com',
                'password' => bcrypt($password)
            ];
        })->toArray());
    }
}
