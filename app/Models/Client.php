<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function dialogs(){
        return $this->hasMany(Dialog::class);
    }

    public function product_feedbacks(){
        return $this->hasMany(Product_feedback::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function client_services(){
        return $this->hasMany(Client_service::class);
    }

    public function services(){
        return $this->belongsToMany(Service::class);
    }

}
