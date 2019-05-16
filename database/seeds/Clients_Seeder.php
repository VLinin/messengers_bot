<?php

use Illuminate\Database\Seeder;

class Clients_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            'fio' => 'Иванов Артем Эдуардович',
            'phone' => '89671599432',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Невальный Никита Павлович',
            'phone' => '89853124567',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Ковриженко Платон Александрович',
            'phone' => '89674532118',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Сагиян Карина Анатольевна',
            'phone' => '89037456412',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Третьяк Марина Сергеевна',
            'phone' => '89034567514',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Кузьмин Антон Юрьевич',
            'phone' => '89673574561',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Кулькина галина Олеговна',
            'phone' => '89673248210',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Сортин Эдуард Александрович',
            'phone' => '89851758564',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Кондейко Алиса Павловна',
            'phone' => '89671499274',
        ]);

        DB::table('clients')->insert([
            'fio' => 'Перов Роман Дмитриевич',
            'phone' => '89031763485',
        ]);

    }
}
