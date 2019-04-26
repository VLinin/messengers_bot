<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function statuses(){
        return $this->belongsToMany(Status::class)->withPivot('comment', 'updated_at','created_at');
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

}
