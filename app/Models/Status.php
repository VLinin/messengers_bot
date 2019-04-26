<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function orders(){
        return $this->belongsToMany(Order::class)->withPivot('comment', 'updated_at','created_at');
    }
}
