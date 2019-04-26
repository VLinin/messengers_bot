<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function distributions(){
        return $this->belongsToMany(Distribution::class);
    }
}
