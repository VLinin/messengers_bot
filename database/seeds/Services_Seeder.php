<?php

use Illuminate\Database\Seeder;

class Services_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'id' => 1,
            'name' => 'Сайт',
            'enable' => false,
        ]);

        DB::table('services')->insert([
            'id' => 2,
            'name' => 'ВКонтакте',
            'enable' => true,
        ]);

        DB::table('services')->insert([
            'id' => 3,
            'name' => 'Telegram',
            'enable' => true,
        ]);

    }
}
