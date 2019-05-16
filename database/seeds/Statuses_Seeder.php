<?php

use Illuminate\Database\Seeder;

class Statuses_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('statuses')->insert([
            'name' => 'Завершен',
        ]);

        DB::table('statuses')->insert([
            'name' => 'Выполняется',
        ]);

        DB::table('statuses')->insert([
            'name' => 'Формирование',
        ]);

    }
}
