<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(Services_Seeder::class);
        $this->call(Categories_Seeder::class);
        $this->call(Clients_Seeder::class);
        $this->call(Statuses_Seeder::class);
        $this->call(Products_Seeder::class);
        $this->call(Dialog_buttons_Seeder::class);
        $this->call(Dialog_stages_Seeder::class);
        $this->call(Feedbacks_Seeder::class);
        $this->call(Orders_Seeder::class);
        $this->call(Distributions_Seeder::class);

    }
}
