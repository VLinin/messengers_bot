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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = array(
            'chat_id' =>  $this->id,
            'text' => $this->text,
            'reply_markup' => $this->keyboard
        );

        $ch = curl_init('https://api.telegram.org/bot' . $this->token . '/sendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result_message=curl_exec($ch);
        curl_close($ch);
        if(isset(json_decode($result_message)['message_id'])){
            if($this->photo!=null){
                $response = array(
                    'chat_id' => $this->id,
                    'photo' => curl_file_create(__DIR__ . '/image.png')
                );

                $ch = curl_init('https://api.telegram.org/bot' . $this->token . '/sendPhoto');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                $res_photo=curl_exec($ch);
                curl_close($ch);
                if(isset(json_decode($res_photo)['message_id'])){
                    Dialog::where('chat_id','=',$this->id)->where('service_id','=',2)->update(['dialog_stage_id' => $this->dialoginfo['next_stage'], 'pre_stage' => $this->dialoginfo['pre_stage'],'spec_info' => $this->dialoginfo['spec_info']]);
                }
            }else{
                Dialog::where('chat_id','=',$this->id)->where('service_id','=',2)->update(['dialog_stage_id' => $this->dialoginfo['next_stage'], 'pre_stage' => $this->dialoginfo['pre_stage'],'spec_info' => $this->dialoginfo['spec_info']]);
            }
        }
    }

}
