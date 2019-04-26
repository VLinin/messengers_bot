<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    public function services(){
        return $this->belongsToMany(Service::class);
    }

    public function images(){
        return $this->belongsToMany(Image::class);
    }
}
