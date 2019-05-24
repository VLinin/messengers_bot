<?php

namespace App\Jobs;

use App\Dialog;
use App\Http\Controllers\vkController;
use App\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiMessagesCantFwdException;
use VK\Exceptions\Api\VKApiMessagesChatBotFeatureException;
use VK\Exceptions\Api\VKApiMessagesChatUserNoAccessException;
use VK\Exceptions\Api\VKApiMessagesContactNotFoundException;
use VK\Exceptions\Api\VKApiMessagesDenySendException;
use VK\Exceptions\Api\VKApiMessagesKeyboardInvalidException;
use VK\Exceptions\Api\VKApiMessagesPrivacyException;
use VK\Exceptions\Api\VKApiMessagesTooLongForwardsException;
use VK\Exceptions\Api\VKApiMessagesTooLongMessageException;
use VK\Exceptions\Api\VKApiMessagesUserBlockedException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class sendToVKJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $id;
    private $text;
    private $photo;
    private $keyboard;
    private $dialoginfo;
    private $token="34743dbbc8c9d33dbde7ea6394b98800fe168dab289a443e5a0f2b4e297a340b490d04f9447312a4c9913";


    /**
     * Create a new job instance.
     *
     * @return void
     */
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
            'message' =>$this->text.' <br> '.$ph_id,   // Что отправляем
            'attachment' => 'photo-'.vkController::$alb_id.'_'.$ph_id,
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
