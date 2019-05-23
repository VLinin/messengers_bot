<?php

use Illuminate\Database\Seeder;

class Feedbacks_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_feedbacks')->insert([
            'text' => 'Отличный продукт, очень понравился. Всем советую попробовать.',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Покупал как подарок, очень понравилось. Цена хоть и выше чем в обычных магазинах, но товар стоящий!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Не понравился сервис, ждал свой заказ неделю!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Продукт качественный, буду покупать здесь еще.',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Очень качественный и удобный сервис, товар также порадовал. Спасибо!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Покупал для дочери, любит сладости. Осталась довольна. Советую.',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Не мог найти этот продукт долгое время! Качество и цена отличные!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Продукт не оправдал ожиданий. Не берите!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Цена слишком высокая для такого продукта!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Порадовал сервис и работа персонала. Заказ офрмил очень быстро через соцсеть, продукт тоже хороший!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Качетсво на уровне, одно из лучших предложений на рынке!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

       DB::table('product_feedbacks')->insert([
            'text' => 'Лучше не находил, заказывайте!',
            'client_id' => random_int(1,10),
            'product_id' => random_int(1,75),
            'service_id' => 2,
        ]);

    }
}
