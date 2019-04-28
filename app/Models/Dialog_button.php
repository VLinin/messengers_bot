<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dialog_button extends Model
{
    public function dialog_stages(){
        return $this->belongsToMany(Dialog_stage::class);
    }
}
