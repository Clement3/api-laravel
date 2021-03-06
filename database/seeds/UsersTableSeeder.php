<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 10; $i++) {
            DB::table('users')->insert([
                'name' => 'user'.$i,
                'email' => 'user'.$i.'@gmail.com',
                'password' => bcrypt('secret'),
                'created_at' => now()
            ]);
        }
    }
}
