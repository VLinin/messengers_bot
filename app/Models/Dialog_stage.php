<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dialog_stage extends Model
{
    public function dialogs(){
        return $this->hasMany(Dialog::class);
    }

    public function dialog_buttons(){
        return $this->hasMany(Dialog_button::class);
    }

    public function children(){
        return $this->hasMany(Dialog_stage::class);
    }
}
