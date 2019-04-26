<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dialog_button extends Model
{
    public function dialog_stage(){
        return $this->belongsTo(Dialog_stage::class);
    }
}
