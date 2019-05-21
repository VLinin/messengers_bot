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
            'id' => 1,
            'name' => 'Завершен',
        ]);

        DB::table('statuses')->insert([
            'id' => 2,
            'name' => 'Выполняется',
        ]);

        DB::table('statuses')->insert([
            'id' => 3,
            'name' => 'Формирование',
        ]);

    }
}
