<?php

use Illuminate\Database\Seeder;

class Dialog_buttons_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dialog_buttons')->insert([
            'id' => 1,
            'sign_text' => 'Начать',
            'color' => 'positive',
            'payload' => 'start',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 2,
            'sign_text' => 'Оформить заказ',
            'color' => 'primary',
            'payload' => 'make_order',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 3,
            'sign_text' => 'Получить информацию',
            'color' => 'default',
            'payload' => 'get_info',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 4,
            'sign_text' => 'Отправить отзыв',
            'color' => 'default',
            'payload' => 'send_feedback',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 5,
            'sign_text' => 'К началу',
            'color' => 'negative',
            'payload' => 'to_begin',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 6,
            'sign_text' => 'Отменить заказ',
            'color' => 'negative',
            'payload' => 'cancel_order',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 7,
            'sign_text' => 'Оформить заказ',
            'color' => 'positive',
            'payload' => 'done_order',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 8,
            'sign_text' => 'Назад',
            'color' => 'negative',
            'payload' => 'back',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 9,
            'sign_text' => 'Добавить в заказ',
            'color' => 'primary',
            'payload' => 'add_to_order',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 10,
            'sign_text' => 'Информация о заказах',
            'color' => 'default',
            'payload' => 'order_info',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 11,
            'sign_text' => 'Ассортимент',
            'color' => 'default',
            'payload' => 'product_list',
        ]);

        DB::table('dialog_buttons')->insert([
            'id' => 12,
            'sign_text' => 'Отмена',
            'color' => 'negative',
            'payload' => 'cancel',
        ]);

    }
}
