<?php

use Illuminate\Database\Seeder;

class Dialog_stages_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dialog_stages')->insert([
            'id' => 1,
            'name' => 'Начальная стадия',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 1,
            'dialog_button_id' => 1
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 2,
            'name' => 'Основное меню',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 2,
            'dialog_button_id' => 2
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 2,
            'dialog_button_id' => 3
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 2,
            'dialog_button_id' => 4
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 3,
            'name' => 'Выбор категории',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 3,
            'dialog_button_id' => 5
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 3,
            'dialog_button_id' => 6
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 3,
            'dialog_button_id' => 7
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 4,
            'name' => 'Выбор товара из категории',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 4,
            'dialog_button_id' => 5
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 4,
            'dialog_button_id' => 6
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 4,
            'dialog_button_id' => 7
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 4,
            'dialog_button_id' => 8
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 5,
            'name' => 'Информация о товаре',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 5,
            'dialog_button_id' => 5
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 5,
            'dialog_button_id' => 6
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 5,
            'dialog_button_id' => 7
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 5,
            'dialog_button_id' => 8
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 5,
            'dialog_button_id' => 9
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 6,
            'name' => 'Ввод количества добавляемого товара',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 6,
            'dialog_button_id' => 5
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 6,
            'dialog_button_id' => 6
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 6,
            'dialog_button_id' => 7
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 6,
            'dialog_button_id' => 8
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 7,
            'name' => 'Меню информации',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 7,
            'dialog_button_id' => 10
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 7,
            'dialog_button_id' => 11
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 8,
            'name' => 'Вывод действующих заказов',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 8,
            'dialog_button_id' => 5
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 8,
            'dialog_button_id' => 8
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 9,
            'name' => 'Информация по заказу',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 9,
            'dialog_button_id' => 5
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 9,
            'dialog_button_id' => 8
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 10,
            'name' => 'Формирование отзыва',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 10,
            'dialog_button_id' => 12
        ]);

        DB::table('dialog_stages')->insert([
            'id' => 11,
            'name' => 'Вывод завершенных заказов',
        ]);
        DB::table('dialog_button_dialog_stage')->insert([
            'dialog_stage_id' => 11,
            'dialog_button_id' => 12
        ]);

    }
}
