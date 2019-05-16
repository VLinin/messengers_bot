<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_feedback extends Model
{
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }


    public function getInfoToShow(){
        $data=\DB::table('product_feedbacks')
            ->join('services','product_feedbacks.service_id','=','services.id')
            ->join('clients','clients.id','=','product_feedbacks.client_id')
            ->join('products','products.id','=','product_feedbacks.product_id')
            ->select('product_feedbacks.id','product_feedbacks.text',
                        'clients.fio','services.name as service','products.name as product')
            ->where('checked','=',0)
            ->get();
        return $data;
    }

    public function getInfoToAnswer($id){
        $data=\DB::table('product_feedbacks')
            ->join('services','product_feedbacks.service_id','=','services.id')
            ->join('clients','clients.id','=','product_feedbacks.client_id')
            ->select('product_feedbacks.id','product_feedbacks.text as text',
                'clients.fio as fio', 'clients.id as client_id','services.id as service_id')
            ->where('product_feedbacks.id','=',$id)
            ->get();
        return $data;
    }

    public function check($id){
        \DB::table('product_feedbacks')->where('id',$id)->update(['checked'=>1]);
    }
}
