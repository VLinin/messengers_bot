<?php

namespace App\Http\Controllers;

use App\Client;
use App\Dialog;
use App\Dialog_stage;
use App\Image;
use App\Jobs\sendToVKJob;
use App\Jobs\uploadPhotoToVkJob;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class vkController extends Controller
{
    private $user_token="365c7ec1ffc49087c9d4bb749e563b5cc7ea8552649ed52c4c7385848684ba6f229099b09adf306764169";
    private $group_id="182538296"; //id группы вк
    public static $alb_id="264978841";

    public function index(Request $request){
        $data = json_decode($request->getContent());
        $confirm_word="64a06e38";
        $scr_key="scr1547";


        if ($data->secret!==$scr_key){
            return null;
        }

        switch ($data->type) {
            case 'confirmation':
                return $confirm_word;
            case 'message_new':
                $peer=$data->object->from_id;
                $stage=$this->dialogTest($peer, $data);
                if ($stage!=0){
                    serviceController::stageProcess($stage, $data, 2);
                }
                return 'ok';
            default:
                return 'ok';
        }
    }

    public function dialogTest($peer, $data){
        $dialog_query=\DB::table('dialogs')
            ->where('dialogs.chat_id','=',$peer)
            ->where('dialogs.service_id','=',2)
            ->select('dialogs.id','dialogs.client_id as client','dialogs.dialog_stage_id as stage')
            ->get();
        if(!isset($dialog_query[0])){
            if (is_numeric($data->object->text) && strlen($data->object->text)==11){
                $client=Client::where('phone','=',$data->object->text)->get();
                if(isset($client[0])){
                    $dialog= new Dialog();
                    $dialog->dialog_stage_id=1;
                    $dialog->service_id=2;
                    $dialog->chat_id=$peer;
                    $dialog->client_id=$client[0]->id;
                    $dialog->save();
                    return 1;
                }else{
                    $clnt_id=\DB::table('clients')->insertGetId([
                        "phone" => $data->object->text
                    ]);
                    $dialog= new Dialog();
                    $dialog->dialog_stage_id=1;
                    $dialog->service_id=2;
                    $dialog->chat_id=$peer;
                    $dialog->client_id=$clnt_id;
                    $dialog->save();
                    return 1;
                }
            }else{
                $dialog= new Dialog();
                $dialog->dialog_stage_id=1;
                $dialog->service_id=2;
                $dialog->chat_id=$peer;
                $dialog->save();
                $text="Для взаимодействия с системой укажите свой мобильный телефон начиная с 8.... 
                        Это позволит связать ваши аккаунты из различных сервисов и осуществлять заказы!";
                sendToVKJob::dispatch($peer, $text, null, $this->makeKeyboardVK(1,$peer,2),['next_stage'=>1,'pre_stage'=>null,'spec_info'=>null]);
                return 0;
            }
        }else{
            if(isset($dialog_query[0]->client)){
                return $dialog_query[0]->stage;
            }else{
                if (is_numeric($data->object->text) && strlen($data->object->text)==11){
                    $client=Client::where('phone','=',$data->object->text)->get();
                    if(isset($client[0])){
                        $dlg=Dialog::find($dialog_query[0]->id);
                        $dlg->client_id=$client[0]->id;
                        $dlg->save();
                        return 1;
                    }else{
                        $clnt_id=\DB::table('clients')->insertGetId([
                            "phone" => $data->object->text
                        ]);
                        $dialog= new Dialog();
                        $dlg=Dialog::find($dialog_query[0]->id);
                        $dlg->client_id=$clnt_id;
                        $dlg->save();
                        return 1;
                    }
                }else{
                    $text="Для взаимодействия с системой укажите свой мобильный телефон начиная с 8.... 
                        Это позволит связать ваши аккаунты из различных сервисов и осуществлять заказы!";
                    sendToVKJob::dispatch($peer, $text, null, $this->makeKeyboardVK(1,$peer,2),['next_stage'=>1,'pre_stage'=>null,'spec_info'=>null]);
                    return 0;
                }
            }
        }
    }

    public static function makeKeyboardVK($stage_id,$from_id,$service_id){
        $kbrd=[
            "one_time" => true,
            "buttons" => []
        ];
        $query=Dialog_stage::find($stage_id)->dialog_buttons()->get();
        foreach ($query as $btn){
            if($btn->id==6 || $btn->id==7){
                $order=\DB::table('dialogs')
                    ->join('clients','clients.id','=','dialogs.client_id')
                    ->join('orders','clients.id','=','orders.client_id')
                    ->join('order_statuses', 'orders.id','=','order_statuses.order_id')
                    ->select('order_statuses.status_id as status','orders.id as order')
                    ->where('dialogs.chat_id','=',$from_id)
                    ->where('dialogs.service_id','=',$service_id)
                    ->where('order_statuses.status_id', '=' ,3)
                    ->get();
                if(isset($order[0])){
                    $kbrd['buttons'][]=[
                        [
                            "action"=> [
                                "type" => "text",
                                "payload" => json_encode(['do' =>$btn->payload], JSON_UNESCAPED_UNICODE),
                                "label" => $btn->sign_text
                            ],
                            "color" => $btn->color
                        ]
                    ];
                }
            }else{
                $kbrd['buttons'][]=[
                    [
                        "action"=> [
                            "type" => "text",
                            "payload" => json_encode(['do' => $btn->payload], JSON_UNESCAPED_UNICODE),
                            "label" => $btn->sign_text
                        ],
                        "color" => $btn->color
                    ]
                ];
            }

        }
        return $kbrd;
    }

    public function uploadPhotosToVk(){
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
