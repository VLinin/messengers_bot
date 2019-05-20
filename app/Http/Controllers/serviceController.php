<?php

namespace App\Http\Controllers;

use App\Client;
use App\Dialog;
use App\Dialog_stage;
use App\Jobs\sendToVKJob;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VK\Client\VKApiClient;

class serviceController extends Controller
{
    public function vk(Request $request){
        $data = json_decode($request->getContent());
        $this->index($data,2);
    }
    public function index($data, $service_id){

        $confirm_word="64a06e38"; //vk
        $scr_key="scr1547"; //vk
        $peer=$data->object->from_id;

        if ($data->secret!==$scr_key){
            return null;
        }

        switch ($data->type) {
            case 'confirmation':
                return '8c9db1bb';
            case 'message_new':
                $dialog_test_result=$this->dialogTest($peer,$service_id, $data);
                if ($dialog_test_result!=0){
                    $this->stageProcess($dialog_test_result, $data, $service_id);
                }
                return 'ok';
            default:
                return 'ok';
        }
    }

    public function dialogTest($peer, $service_id, $data){
        $dialog_query=\DB::table('dialogs')
            ->where('dialogs.chat_id','=',$peer)
            ->where('dialogs.service_id','=',$service_id)
            ->select('dialogs.id','dialogs.client_id as client','dialogs.dialog_stage_id as stage')
            ->get();
        if(!isset($dialog_query[0])){
            if (is_numeric($data->object->text) && strlen($data->object->text)==11){
                $client=Client::where('phone','=',$data->object->text)->get();
                if(isset($client[0])){
                    $dialog= new Dialog();
                    $dialog->dialog_stage_id=1;
                    $dialog->service_id=$service_id;
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
                    $dialog->service_id=$service_id;
                    $dialog->chat_id=$peer;
                    $dialog->client_id=$clnt_id;
                    $dialog->save();
                    return 1;
                }
            }else{
                $dialog= new Dialog();
                $dialog->dialog_stage_id=1;
                $dialog->service_id=$service_id;
                $dialog->chat_id=$peer;
                $dialog->save();
                $text="Для взаимодействия с системой укажите свой мобильный телефон начиная с 8.... 
                        Это позволит связать ваши аккаунты из различных сервисов и осуществлять заказы!";
                sendToVKJob::dispatch($peer, $text, null, $this->makeKeyboardVK(1));
                return 0;
            }
        }else{
            if(isset($dialog_query[0]->client)){
                return 1;
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
                    sendToVKJob::dispatch($peer, $text, null, $this->makeKeyboardVK(1));
                    return 0;
                }
            }
        }
    }

    public function makeKeyboardVK($id){
        $kbrd=[
            "one_time" => true,
            "buttons" => []
        ];
        $query=Dialog_stage::find($id)->dialog_buttons();
        foreach ($query as $btn){
            $kbrd['buttons'][]=[
                [
                    "action"=> [
                        "type" => "text",
                        "payload" => $query[0]->payload,
                        "label" => $query[0]->sign_text
                    ],
                    "color" => $query[0]->color
                ]
            ];
        }
        return $kbrd;
    }

    public function stageProcess($id, $data, $service_id){
        switch ($id){
            case 1:
                break;

            case 2:
                break;

            case 3:
                break;

            case 4:
                break;

            case 5:
                break;

            case 6:
                break;

            case 7:
                break;

            case 8:
                break;

            case 9:
                break;

            case 10:
                break;

            case 11:
                break;
        }

    }
}
