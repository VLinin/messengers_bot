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
                        $this->sendWithPhoto($ph_path);
                    }else{
                        $this->sendWithoutPhoto();
                    }
                }
            }else{
                $this->sendWithoutPhoto();
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

    public function sendWithPhoto($path){
        $url = 'https://api.vk.com/method/messages.send';
        $params = array(
            'random_id' => random_int(0,234456),
            'peer_id' => $this->id,    // Кому отправляем
            'message' =>$this->text,   // Что отправляем
            'attachment' => 'photo-182538296_'.$path,
            'access_token' => '34743dbbc8c9d33dbde7ea6394b98800fe168dab289a443e5a0f2b4e297a340b490d04f9447312a4c9913',  // access_token можно вбить хардкодом, если работа будет идти из под одного юзера
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
    }

    public function uploadphoto($vk){
        $user_token="365c7ec1ffc49087c9d4bb749e563b5cc7ea8552649ed52c4c7385848684ba6f229099b09adf306764169";
        $group_id="182538296"; //id группы вк
        $alb_id="264876553";
        //получаем адресс сервиса для загрузки фото
        $uploadServer=$vk->photos()->getUploadServer($user_token,array("group_id"=>$group_id,"album_id"=>$alb_id));
        $uploadURL=$uploadServer["upload_url"];

        //загружаем фото
        try {
            $cfile = curl_file_create(\Storage::disk('public')->path('pr1.png'), 'image/png', 'temp.png');
            dump($cfile);
        } catch (FileNotFoundException $e) {
            return null;
        }
        $ch = curl_init($uploadURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("file" => $cfile));
        $result = json_decode(curl_exec($ch),true);
        curl_close($ch);
        dump($result);
        //сохраняем фото
        $photo=$vk->photos()->save($user_token,array(
            "group_id"=>$group_id,
            "album_id"=>$alb_id,
            "photos_list"=>$result['photos_list'],
            "server"=>$result['server'],
            "hash"=>$result['hash'],
        ));
        Image::where('path','=',$this->photo)->update('vk',$photo[0]['id']);
        return $photo[0]['id'];
    }

    public function sendWithoutPhoto(){
        $url = 'https://api.vk.com/method/messages.send';
        $params = array(
            'random_id' => random_int(0,234456),
            'peer_id' => $this->id,    // Кому отправляем
            'message' =>$this->text,   // Что отправляем
            'access_token' => '34743dbbc8c9d33dbde7ea6394b98800fe168dab289a443e5a0f2b4e297a340b490d04f9447312a4c9913',  // access_token можно вбить хардкодом, если работа будет идти из под одного юзера
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
    }

}
