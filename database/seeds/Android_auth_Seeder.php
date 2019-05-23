<?php

use Illuminate\Database\Seeder;

class Android_auth_Seeder extends Seeder
{

    public function run()
    {
        DB::table('android_auth')->insert([
            'login' => 'mtg_admin',
            'password' => md5('admin'),
        ]);

        DB::table('android_auth')->insert([
            'login' => 'mtg_manager',
            'password' => md5('secret'),
        ]);

        DB::table('android_auth')->insert([
            'login' => 'mtg_main',
            'password' => md5('superPass'),
        ]);
    }
}
