<?php

use Illuminate\Database\Seeder;

class Android_auth_Seeder extends Seeder
{

    public function run()
    {
        DB::table('android_auth')->insert([
            'login' => 'mtg_admin',
            'password' => 'admin',
        ]);

        DB::table('android_auth')->insert([
            'login' => 'mtg_manager',
            'password' => 'secret',
        ]);

        DB::table('android_auth')->insert([
            'login' => 'mtg_main',
            'password' => 'superPass',
        ]);
    }
}
