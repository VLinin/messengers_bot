<?php

use Illuminate\Database\Seeder;

class Orders_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            'id' => 1,
            'client_id' => random_int(1,10),
            'service_id' => 2,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 1,
            'status_id'=> 2
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' => 1
        ]);

        DB::table('orders')->insert([
            'id' => 2,
            'client_id' => random_int(1,10),
            'service_id' => 3,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 2,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' => 2
        ]);

        DB::table('orders')->insert([
            'id' => 3,
            'client_id' => random_int(1,10),
            'service_id' => 2,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 3,
            'status_id'=> 2
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1, 10),
            'order_id' => 3
        ]);

        DB::table('orders')->insert([
            'id' => 4,
            'client_id' => random_int(1,10),
            'service_id' => 3,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 4,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' => 4
        ]);

        DB::table('orders')->insert([
            'id' => 5,
            'client_id' => random_int(1,10),
            'service_id' => 2,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 5,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' =>5
        ]);

        DB::table('orders')->insert([
            'id' => 6,
            'client_id' => random_int(1,10),
            'service_id' => 1,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 6,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' => 6
        ]);

        DB::table('orders')->insert([
            'id' => 7,
            'client_id' => random_int(1,10),
            'service_id' => 2,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 7,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' =>7
        ]);

        DB::table('orders')->insert([
            'id' => 8,
            'client_id' => random_int(1,10),
            'service_id' => 3,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 8,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' => 8
        ]);

        DB::table('orders')->insert([
            'id' => 9,
            'client_id' => random_int(1,10),
            'service_id' => 2,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 9,
            'status_id'=> 2
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' => 9
        ]);

        DB::table('orders')->insert([
            'id' => 10,
            'client_id' => random_int(1,10),
            'service_id' => 1,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 10,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' => 10
        ]);

        DB::table('orders')->insert([
            'id' => 11,
            'client_id' => random_int(1,10),
            'service_id' => 3,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 11,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' =>random_int(1,75),
            'order_id' =>11
        ]);

        DB::table('orders')->insert([
            'id' => 12,
            'client_id' => random_int(1,10),
            'service_id' => 2,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 12,
            'status_id'=> 2
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' =>12
        ]);

        DB::table('orders')->insert([
            'id' => 13,
            'client_id' => random_int(1,10),
            'service_id' => 2,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 13,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' =>13
        ]);

        DB::table('orders')->insert([
            'id' => 14,
            'client_id' => random_int(1,10),
            'service_id' => 3,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 14,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' => random_int(1,75),
            'order_id' => 14
        ]);

        DB::table('orders')->insert([
            'id' => 15,
            'client_id' => random_int(1,10),
            'service_id' => 2,
            'created_at' => Carbon\Carbon::now()
        ]);
        DB::table('order_statuses')->insert([
            'order_id' => 15,
            'status_id'=> 1
        ]);
        DB::table('order_products')->insert([
            'product_id' =>random_int(1,75),
            'order_id' => 15
        ]);

    }
}
