<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function product_feedbacks(){
        return $this->hasMany(Product_feedback::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }

    public function images(){
        return $this->belongsToMany(Image::class);
    }
}
