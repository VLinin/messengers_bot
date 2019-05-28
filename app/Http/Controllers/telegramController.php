<?php

namespace App\Http\Controllers;

use App\Client;
use App\Dialog;
use App\Dialog_stage;
use App\Jobs\sendToTlgrmJob;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class telegramController extends Controller
{
    public  $telgrToken='845701278:AAG-eaVtv4oNOjhYOSHGaNU6DPvb-ml3P2k';
    public  $message;
    public function index(Request $request){

        $set_webhook='https://api.telegram.org/bot845701278:AAG-eaVtv4oNOjhYOSHGaNU6DPvb-ml3P2k/setwebhook?url=https://xn--h1aahjb.xn--p1acf/tlgrm';
        $data = json_decode($request->getContent());

        $response = array(
            'chat_id' =>  ['message']['chat']['id'],
            'text' => $data,
        );

        $ch = curl_init('https://api.telegram.org/bot' . $this->token . '/sendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result_message=curl_exec($ch);
        curl_close($ch);
//        // если это просто объект message
//        if (array_key_exists('message', $data)) {
//            // получаем id чата
//            $peer = $data['message']['chat']['id'];
//            // текстовое значение
//            $this->message = $data['message']['text'];
//            $payload=null;
//            // если это объект callback_query
//        } elseif (array_key_exists('callback_query', $data)) {
//            $peer = $data['callback_query']['message']['chat']['id'];
//            $payload = $data['callback_query']['data'];
//            $this->message = null;
//        }
//
//        $stage=$this->dialogTest($peer, $data);
//        if ($stage!=0){
//            serviceController::stageProcess($stage, $data, 2);
//        }
//        return 'ok';
    }

    public function dialogTest($peer, $data){
        $dialog_query=\DB::table('dialogs')
            ->where('dialogs.chat_id','=',$peer)
            ->where('dialogs.service_id','=',2)
            ->select('dialogs.id','dialogs.client_id as client','dialogs.dialog_stage_id as stage')
            ->get();
        if(!isset($dialog_query[0])){
            if (is_numeric($this->message) && strlen($this->message)==11){
                $client=Client::where('phone','=',$data->object->text)->get();
                if(isset($client[0])){
                    $dialog= new Dialog();
                    $dialog->dialog_stage_id=1;
                    $dialog->service_id=3;
                    $dialog->chat_id=$peer;
                    $dialog->client_id=$client[0]->id;
                    $dialog->save();
                    return 1;
                }else{
                    $clnt_id=\DB::table('clients')->insertGetId([
                        "phone" => $this->message
                    ]);
                    $dialog= new Dialog();
                    $dialog->dialog_stage_id=1;
                    $dialog->service_id=3;
                    $dialog->chat_id=$peer;
                    $dialog->client_id=$clnt_id;
                    $dialog->save();
                    return 1;
                }
            }else{
                $dialog= new Dialog();
                $dialog->dialog_stage_id=1;
                $dialog->service_id=3;
                $dialog->chat_id=$peer;
                $dialog->save();
                $text="Для взаимодействия с системой укажите свой мобильный телефон начиная с 8.... 
                        Это позволит связать ваши аккаунты из различных сервисов и осуществлять заказы!";
                sendToTlgrmJob::dispatch($peer, $text, null, $this->makeKeyboardTlgrm(1,$peer,2),['next_stage'=>1,'pre_stage'=>null,'spec_info'=>null]);
                return 0;
            }
        }else{
            if(isset($dialog_query[0]->client)){
                return $dialog_query[0]->stage;
            }else{
                if (is_numeric($data->object->text) && strlen($data->object->text)==11){
                    $client=Client::where('phone','=',$this->message)->get();
                    if(isset($client[0])){
                        $dlg=Dialog::find($dialog_query[0]->id);
                        $dlg->client_id=$client[0]->id;
                        $dlg->save();
                        return 1;
                    }else{
                        $clnt_id=\DB::table('clients')->insertGetId([
                            "phone" => $this->message
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
                    sendToTlgrmJob::dispatch($peer, $text, null, $this->makeKeyboardTlgrm(1,$peer,2),['next_stage'=>1,'pre_stage'=>null,'spec_info'=>null]);
                    return 0;
                }
            }
        }
    }

    public static function makeKeyboardTlgrm($stage_id,$from_id,$service_id){
        $kbrd=$keyboard = [
            "keyboard" => [],
            "one_time_keyboard" => true,
            "resize_keyboard" => true
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
                    $kbrd['keyboard'][]=[
                        [
                            "text" => $btn->sign_text,
                            "callback_data" =>$btn->payload
                        ]
                    ];
                }
            }else{
                $kbrd['keyboard'][]=[
                    [
                        "text" => $btn->sign_text,
                        "callback_data" =>$btn->payload
                    ]
                ];
            }
        }
        return $kbrd;
    }


}