<?php

use Illuminate\Database\Seeder;

class Distributions_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('distributions')->insert([
            'id' => 1,
            'text' => 'Приветствуем! <br>
            У нас акция! <br>
            Только до конца месяца успейте приобрести товары со скидкой до 50%!
            Порадуйте себя или своих близких. В нашем ассорименте есть продукты
            на все случаи в жизни! <br>
            Не упустите свой шанс!!!',
            'run_date' => '2020-01-01',
        ]);

        DB::table('distribution_services')->insert([
            'distribution_id' => 1,
            'service_id' => 1
        ]);

        DB::table('distributions')->insert([
            'id' => 2,
            'text' => 'Приветствуем! <br> 
            А вот и скидки! <br>
            Только до конца месяца успейте приобрести товары со скидкой до 50%!
            Новый год уже близко. В нашем ассорименте есть продукты
            на все случаи в жизни! Они смогут стать отличным подарком в эти замечательные праздники!',
            'run_date' => '2020-12-15',
        ]);

        DB::table('distribution_services')->insert([
            'distribution_id' => 2,
            'service_id' => 1
        ]);

        DB::table('distributions')->insert([
            'id' => 3,
            'text' => 'Приветствуем! <br> 
            Уроки заканчиваются! <br>
            Подарите себе кусочек счастья в эти теплые деньки. Поздравьте одноклассников/одногруппников!
            Пусть они запомнят вас и чудесный вкус нашей продукции!',
            'run_date' => '2020-05-20',
        ]);

        DB::table('distribution_services')->insert([
            'distribution_id' => 3,
            'service_id' => 1
        ]);
    }
}
