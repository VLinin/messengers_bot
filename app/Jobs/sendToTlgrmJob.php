<?php

namespace App\Jobs;

use App\Dialog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class sendToTlgrmJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $text;
    private $photo;
    private $keyboard;
    private $dialoginfo;
    private $token="3845701278:AAG-eaVtv4oNOjhYOSHGaNU6DPvb-ml3P2k";

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
        $proxy='64.118.88.39:19485';
        if($this->keyboard==null){
            $response = array(
                'chat_id' =>  $this->id,
                'text' => $this->text,
            );
        }else{
            $response = array(
                'chat_id' =>  $this->id,
                'text' => $this->text,
                'reply_markup' => $this->keyboard
            );
        }


        $ch = curl_init();
        $url = 'https://api.telegram.org/bot' . $this->token . '/sendMessage';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, "socks5://$proxy");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($response));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);


//        if(json_decode($result)->ok){
//            if($this->photo!=null){
//                $response = array(
//                    'chat_id' => $this->id,
//                    'photo' => curl_file_create(__DIR__ . '/image.png')
//                );
//
//                $ch = curl_init();
//                $url = 'https://api.telegram.org/bot' . $this->token . '/sendMessage';
//                curl_setopt($ch, CURLOPT_URL, $url);
//                curl_setopt($ch, CURLOPT_PROXY, "socks5://$proxy");
//                curl_setopt($ch, CURLOPT_HEADER, false);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//                curl_setopt($ch, CURLOPT_POST, 1);
//                curl_setopt($ch, CURLOPT_POSTFIELDS, ($response));
//                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//                $result = curl_exec($ch);
//
//                Dialog::where('chat_id','=',$this->id)->where('service_id','=',3)->update(['dialog_stage_id' => $this->dialoginfo['next_stage'], 'pre_stage' => $this->dialoginfo['pre_stage'],'spec_info' => $this->dialoginfo['spec_info']]);
//
//            }else{
//                Dialog::where('chat_id','=',$this->id)->where('service_id','=',3)->update(['dialog_stage_id' => $this->dialoginfo['next_stage'], 'pre_stage' => $this->dialoginfo['pre_stage'],'spec_info' => $this->dialoginfo['spec_info']]);
//            }
//        }
        return http_response_code(200);
    }

}
