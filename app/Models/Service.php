<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function dialogs(){
        return $this->hasMany(Dialog::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function distributions(){
        return $this->belongsToMany(Distribution::class);
    }

    public function changeToken($id,$token){
        \DB::table('services')->where('id',$id)->update(['token'=>$token]);
    }

}
