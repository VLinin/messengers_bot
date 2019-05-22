<?php

namespace App\Jobs;

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
    private $token="34743dbbc8c9d33dbde7ea6394b98800fe168dab289a443e5a0f2b4e297a340b490d04f9447312a4c9913";
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $text,$photo, $keyboard)
    {
        $this->text=$text;
        $this->id=$id;
        $this->photo=$photo;
        $this->keyboard=$keyboard;
    }

    public function handle()
    {
        $vk = new VKApiClient();
        try {
            if($this->photo != null){
                $vkQuery=Image::where('path','=',$this->photo)->select('vk')->get();
                if($vkQuery[0]->vk != null){
                    $this->sendWithPhoto($vk, $vkQuery[0]->vk);
                }else{
                    $ph_path=$this->uploadphoto($vk);
                    if($ph_path!=null){
                        $this->sendWithPhoto($vk, $ph_path);
                    }else{
                        $this->sendWithoutPhoto($vk);
                    }
                }
            }else{
                $this->sendWithoutPhoto($vk);
            }
        } catch (VKApiMessagesCantFwdException $e) {
        } catch (VKApiMessagesChatBotFeatureException $e) {
        } catch (VKApiMessagesChatUserNoAccessException $e) {
        } catch (VKApiMessagesContactNotFoundException $e) {
        } catch (VKApiMessagesDenySendException $e) {
        } catch (VKApiMessagesKeyboardInvalidException $e) {
        } catch (VKApiMessagesPrivacyException $e) {
        } catch (VKApiMessagesTooLongForwardsException $e) {
        } catch (VKApiMessagesTooLongMessageException $e) {
        } catch (VKApiMessagesUserBlockedException $e) {
        } catch (VKApiException $e) {
        } catch (VKClientException $e) {
        }
    }

    public function sendWithPhoto($vk, $path){
        $vk->messages()->send($this->token,
            [
                'peer_id' => $this->id,
                'message' => urlencode($this->text),
                'attachment' => $path,
                'v' => '5.95',
                'keyboard' => $this->keyboard
            ]);
    }

    public function sendWithoutPhoto($vk){
        $vk->messages()->send($this->token,
            [
                'peer_id' => $this->id,
                'message' => urlencode($this->text),
                'v' => '5.95',
                'keyboard' => $this->keyboard
            ]);
    }

    public function uploadphoto($vk){
        $group_id="182538296"; //id группы вк
        $alb_id="264876553";
        //получаем адресс сервиса для загрузки фото
        $uploadServer=$vk->photos()->getUploadServer($this->token,array("group_id"=>$group_id,"album_id"=>$alb_id));
        $uploadURL=$uploadServer["upload_url"];
        //загружаем фото
        try {
            $cfile = curl_file_create(\Storage::get($this->photo), 'image/jpeg', 'temp.jpg');
        } catch (FileNotFoundException $e) {
            return null;
        }
        $ch = curl_init($uploadURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("file" => $cfile));
        $result = json_decode(curl_exec($ch),true);
        curl_close($ch);
        //сохраняем фото
        $photo=$vk->photos()->save($this->token,array(
            "group_id"=>$group_id,
            "album_id"=>$alb_id,
            "photo"=>$result['photo'],
            "server"=>$result['server'],
            "hash"=>$result['hash'],
            ));
        Image::where('path','=',$this->photo)->update('vk',$photo[0]['id']);
        return $photo[0]['id'];
    }

}
