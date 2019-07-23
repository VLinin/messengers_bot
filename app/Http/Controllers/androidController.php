<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Routing\Controller;

class androidController extends Controller
{
    public function auth(){
        $records=\DB::table('android_auth')->select('login','password')->get();
        return json_encode($records);
    }

    public function orders(){
        $records=\DB::table('orders')
            ->join('clients','clients.id','=','orders.client_id')
            ->join('services','services.id','=','orders.service_id')
            ->join('order_statuses','order_statuses.order_id','=','orders.id')
            ->where('order_statuses.status_id','=',2)
            ->select(
                'orders.id as order_number',
                'orders.created_at as date',
                'clients.fio as fio',
                'clients.phone as phone',
                'services.name as service'
            )
            ->get();
        $array=$records->toArray();
        $main=[];
        for ($i=0;$i<count($array);$i++){
            $main[]=[
                'order_number'=>$array[$i]->order_number,
                "date"=>$array[$i]->date,
                "fio"=>$array[$i]->fio,
                "phone"=>$array[$i]->phone,
                "service"=>$array[$i]->service,
                "cart"=>\DB::table('products')
                    ->join('order_products','order_products.product_id','=','products.id')
                    ->where('order_products.order_id','=',$array[$i]->order_number)
                    ->select(
                        'products.name as product',
                        'products.description as description',
                        'products.price as price',
                        'order_products.amount as amount'
                    )->get()->toArray()
            ];
        }

        return json_encode($main, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @return string
     */
    public function products(){
        $records=Product::all();
        $array=$records->toArray();
        for ($i=0;$i<count($array);$i++){
            $array[$i]=array_merge($array[$i], ['current_amount'=>random_int(0,150)]);
        }

        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }
}
