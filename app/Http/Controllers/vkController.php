<?php

namespace App\Http\Controllers;

use App\Client;
use App\Dialog;
use App\Dialog_stage;
use App\Jobs\sendToVKJob;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class vkController extends Controller
{
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
                sendToVKJob::dispatch($peer, $text, null, $this->makeKeyboardVK(1,$peer,2));
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
                    sendToVKJob::dispatch($peer, $text, null, $this->makeKeyboardVK(1,$peer,2));
                    return 0;
                }
            }
        }
    }

    public static function makeKeyboardVK($id,$from_id,$service_id){
        $kbrd=[
            "one_time" => true,
            "buttons" => []
        ];
        $query=Dialog_stage::find($id)->dialog_buttons()->get();
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
                                "payload" => "{'do':'".$query[0]->payload."'}",
                                "label" => $query[0]->sign_text
                            ],
                            "color" => $query[0]->color
                        ]
                    ];
                }
            }else{
                $kbrd['buttons'][]=[
                    [
                        "action"=> [
                            "type" => "text",
                            "payload" => "{'do':'".$query[0]->payload."'}",
                            "label" => $query[0]->sign_text
                        ],
                        "color" => $query[0]->color
                    ]
                ];
            }

        }
        return $kbrd;
    }
}
