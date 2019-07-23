<?php

namespace App\Jobs;

use App\Dialog;
use App\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class sendToVKJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $id;
    private $text;
    private $photo;
    private $keyboard;
    private $dialoginfo;
    private $token="";


    public function __construct($id, $text,$photo, $keyboard, $dialoginfo)
    {
        $this->text=$text;
        $this->id=$id;
        $this->photo=$photo;
        $this->keyboard=$keyboard;
        $this->dialoginfo=$dialoginfo;
    }

    public function handle()
    {
        if($this->photo != null){

            $vkQuery=Image::where('path','=',$this->photo)->select('vk')->get();
            if(isset($vkQuery[0]->vk)){
                $result=$this->sendWithPhoto($vkQuery[0]->vk);
            }else{
                $result=$this->sendWithoutPhoto();
            }
        }else{
            $result=$this->sendWithoutPhoto();
        }
        if($result){
            Dialog::where('chat_id','=',$this->id)->where('service_id','=',2)->update(['dialog_stage_id' => $this->dialoginfo['next_stage'], 'pre_stage' => $this->dialoginfo['pre_stage'],'spec_info' => $this->dialoginfo['spec_info']]);
        }
    }

    public function sendWithPhoto($ph_id){
        $url = 'https://api.vk.com/method/messages.send';
        $params = array(
            'random_id' => random_int(0,234456),
            'peer_id' => $this->id,    // Кому отправляем
            'message' =>$this->text,   // Что отправляем
            'attachment' => 'photo-182538296_'.$ph_id,
            'access_token' => $this->token,
            'keyboard' => json_encode($this->keyboard, JSON_UNESCAPED_UNICODE),
            'v' => '5.95',
        );
        // В $result вернется id отправленного сообщения
        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));
        if(isset(json_decode($result)->error)){
            return false;
        }else{
            return true;
        }
    }

    public function sendWithoutPhoto(){
        $url = 'https://api.vk.com/method/messages.send';
        $params = array(
            'random_id' => random_int(0,234456),
            'peer_id' => $this->id,    // Кому отправляем
            'message' =>$this->text,   // Что отправляем
            'access_token' => $this->token,
            'keyboard' => json_encode($this->keyboard, JSON_UNESCAPED_UNICODE),
            'v' => '5.95',
        );

        // В $result вернется id отправленного сообщения
        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));

        if(isset(json_decode($result)->error)){
            return false;
        }else{
            return true;
        }
    }

}
