<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dialog extends Model
{
    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function dialog_stage(){
        return $this->belongsTo(Dialog_stage::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }
}
