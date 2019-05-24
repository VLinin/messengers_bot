<?php

namespace App\Jobs;

use App\Http\Controllers\vkController;
use App\Image;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class uploadPhotoToVkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_token="365c7ec1ffc49087c9d4bb749e563b5cc7ea8552649ed52c4c7385848684ba6f229099b09adf306764169";
    private $group_id="182538296"; //id группы вк
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function handle()
    {
        $images=Image::all();
        foreach ($images as $image){
            $res=$this->getUploadServer();
            if($res!=null){
                $res=$this->uploadPhoto($res, $image->path);
                if ($res!=null){
                    $this->savePhoto($res, $image->path);
                }
            }
        }
    }

    public function getUploadServer(){
        $url = 'https://api.vk.com/method/photos.getUploadServer';

        //получаем адресс сервиса для загрузки фото
        $params = array(
            "group_id" => $this->group_id,
            "album_id" => vkController::$alb_id,
            'access_token' => $this->user_token,
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
        if(isset(json_decode($result)->response)){
            return json_decode($result)->response->upload_url;
        }else{
            return null;
        }
    }

    public function uploadPhoto($server_url,$path){
        //загружаем фото
        try {
            $cfile = curl_file_create(\Storage::disk('public')->path($path), 'image/png', 'temp.png');
            dump($cfile);
        } catch (FileNotFoundException $e) {
            return null;
        }
        $ch = curl_init($server_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("file" => $cfile));
        $result = json_decode(curl_exec($ch),true);
        curl_close($ch);
        if(isset($result['photos_list'])){
            return $result;
        }else{
            return null;
        }
    }

    public function savePhoto($result, $path){

        //сохраняем фото
        $url = 'https://api.vk.com/method/photos.save';
        $params = array(
            "group_id"=>$this->group_id,
            "album_id"=>vkController::$alb_id,
            "photos_list"=>$result['photos_list'],
            "server"=>$result['server'],
            "hash"=>$result['hash'],
            'access_token' => $this->user_token,
            'v' => '5.95',
        );
        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));
        if(isset(json_decode($result)->response[0])){
            $photo_id=json_decode($result)->response[0]->id;
            DB::table('images')->where('path','=',$path)->update(['vk'=>$photo_id]);
            return $photo_id;
        }else{
            return null;
        }
    }
}
