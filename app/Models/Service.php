<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function dialogs(){
        return $this->hasMany(Dialog::class);
    }

    public function clients(){
        return $this->belongsToMany(Client::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function distributions(){
        return $this->belongsToMany(Distribution::class);
    }
}
