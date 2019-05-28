<?php

namespace App\Http\Controllers;

use App\Category;
use App\Dialog;
use App\Jobs\sendToTlgrmJob;
use App\Jobs\sendToVKJob;
use App\Order;
use App\Product;
use Carbon\Carbon;
use Illuminate\Routing\Controller;


class serviceController extends Controller
{

    public static function stageProcess($stage, $data, $service_id){
        if($service_id == 2){
            if(isset($data->object->payload)){
                $payload = json_decode($data->object->payload)->do;
            }else{
                $payload=null;
            }
            $message = $data->object->text;
            $from_id=$data->object->from_id;
        }
        if (isset($data->message)) {
            // получаем id чата
            $from_id = $data->message->chat->id;
            // текстовое значение
            $message = $data->message->text;
            $payload=null;
            // если это объект callback_query
        } elseif (isset($data->callback_query)) {
            $from_id = $data->callback_query->message->chat->id;
            $payload = $data->callback_query->data;
            $message = null;
        }


        switch ($stage){
            case 1:             //Начальная стадия
                //to 2 stage
                $text="Приступим к взаимодействию! <br>
                        В этом чате можно осуществлять заказы, отправлять отзывы и получать интересную информацию!
                        Для работы используйте кнопки с предоставленными вариантами.
                      ";
                if($service_id == 2){
                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>1,'spec_info'=>null]);
                }
                if($service_id == 3){
                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>1,'spec_info'=>null]);
                }
                break;

            case 2:             //Основное меню
                switch ($payload){
                    case 'make_order':    //to 3
                        $categories=Category::all();
                        $text='Выберите категорию из списка и отправите её номер: <br> ';
                        foreach ($categories as $category){
                            $text=$text.$category->id.") ".$category->name.'. <br> ';
                        }
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>2,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>2,'spec_info'=>null]);
                        }
                        break;
                    case 'get_info':      //to 7

                        $text='Выберите тип информации, которую хотите получить, используя варианты на кнопках!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>2,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>2,'spec_info'=>null]);
                        }
                        break;
                    case 'send_feedback': //to 11

                        $client_id=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('client_id')->get())[0]->client_id;
                        $orders=\DB::table('orders')->join('order_statuses','orders.id','=','order_statuses.order_id')
                            ->where('orders.client_id','=',$client_id)->where('orders.service_id','=',$service_id)
                            ->select('orders.created_at','order_statuses.updated_at' ,'orders.id')->get();
                        if ($orders->toArray() == []){
                            $text='Список заказов пуст. 
                            Пожалуйста, воспользуйтесь кнопкой для возвращения к главному меню!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(12,$from_id,$service_id),['next_stage'=>12,'pre_stage'=>2,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(12,$from_id,$service_id),['next_stage'=>12,'pre_stage'=>2,'spec_info'=>null]);
                            }
                        }else {
                            $text = 'Выберите заказ из списка и отправьте его номер. <br> Список заказов: <br> ';
                            foreach ($orders as $order) {
                                $text = $text . $order->id . ') Заказ от ' . $order->created_at . ' <br>';
                                if ($order->updated_at != null) {
                                    $text = $text . ' Последнее изменение от ' . $order->updated_at . ' <br> ';
                                }
                            }
                            if ($service_id == 2) {
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11, $from_id, $service_id), ['next_stage' => 11, 'pre_stage' => 2, 'spec_info' => null]);
                            }
                            if ($service_id == 3) {
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(11, $from_id, $service_id), ['next_stage' => 11, 'pre_stage' => 2, 'spec_info' => null]);
                            }
                        }
                        break;
                    default:
                        $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>null,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>null,'spec_info'=>null]);
                        }
                }
                break;

            case 3:             //Выбор категории
                if($payload == null){ //to 4
                    if (is_numeric($message)){
                        $category=Category::find($message);
                        if(isset($category->id)){

                            $text='Выберите товар из списка и отправьте его номер: <br> ';
                            $products=Product::all()->where('category_id','=',$category->id);
                            foreach ($products as $product){
                                $text=$text.$product->id.") ".$product->name.'. - '.$product->price.'р. <br> ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(4,$from_id,$service_id),['next_stage'=>4,'pre_stage'=>3,'spec_info'=>$category->id]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(4,$from_id,$service_id),['next_stage'=>4,'pre_stage'=>3,'spec_info'=>$category->id]);
                            }
                        }else{
                            $text='Такой категории у нас нет. Повторите выбор!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>null,'spec_info'=>null]);
                            }
                        }
                    }else{
                        $text='Такой категории у нас нет. Повторите выбор!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>null,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>null,'spec_info'=>null]);
                        }
                    }
                }else{
                    switch ($payload){
                        case 'to_begin': //to 2
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
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                            }
                            break;
                        case 'cancel_order':  //to 2
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
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                            }
                            break;
                        case 'done_order':    //to 2
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
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->update(['status_id'=>2]);
                                $text="Вы вернулись в главное меню! Формирование заказа завершено, он принят в обработку.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                            }
                            break;

                    }
                }
                break;

            case 4:             //Выбор товара из категории
                if($payload == null){ //to 5
                    if (is_numeric($message)){
                        $product=Product::find($message);
                        if(isset($product->id)){
                            $text='';
                            $d_info=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('spec_info','client_id')->get())[0];
                            if($product->category_id!=$d_info->spec_info){
                                $text='Выбранный товар не из этой категории, но тоже у нас имеется! <br> ';
                            }
                            $image_path=(\DB::table('images')->join('image_products','image_products.image_id','=','images.id')
                                ->where('image_products.product_id','=',$message)->get())[0]->path;
                            $text=$text.$product->name.' - '.$product->price.'р <br> '.$product->description;
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, $image_path, vkController::makeKeyboardVK(5,$from_id,$service_id),['next_stage'=>5,'pre_stage'=>4,'spec_info'=>$message]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(5,$from_id,$service_id),['next_stage'=>5,'pre_stage'=>4,'spec_info'=>$message]);
                            }
                        }else{
                            $text='Такого товара у нас нет. Повторите выбор!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(4,$from_id,$service_id),['next_stage'=>4,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(4,$from_id,$service_id),['next_stage'=>4,'pre_stage'=>null,'spec_info'=>null]);
                            }
                        }
                    }else{
                        $text='Такого товара у нас нет. Повторите выбор!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(4,$from_id,$service_id),['next_stage'=>4,'pre_stage'=>null,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(4,$from_id,$service_id),['next_stage'=>4,'pre_stage'=>null,'spec_info'=>null]);
                        }
                    }
                }else{
                    switch ($payload){
                        case 'back':          //to 3
                            $categories=Category::all();
                            $text='Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер: <br> ';
                            foreach ($categories as $category){
                                $text=$text.$category->id.") ".$category->name.'. <br> ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>4,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>4,'spec_info'=>null]);
                            }
                            break;
                        case 'to_begin':      //to 2
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
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                            }
                            break;
                        case 'cancel_order':  //to 2
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
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                            }
                            break;
                        case 'done_order':    //to 2
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
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->update(['status_id'=>2]);
                                $text="Вы вернулись в главное меню! Формирование заказа завершено, он принят в обработку.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                            }
                            break;
                    }
                }

                break;

            case 5:             //Информация о товаре
                switch ($payload){
                    case 'add_to_order':  //to 6
                        $d_info=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('spec_info','client_id')->get())[0];
                        //проверка существования заказа
                        $order=\DB::table('orders')->join('order_statuses','orders.id','=','order_statuses.order_id')
                            ->where('order_statuses.status_id','=',3)->where('orders.client_id','=',$d_info->client_id)
                            ->select('orders.id')->get();;
                            if (isset($order[0])){
                                $order_id=$order[0]->id;
                            }else{
                                //создание заказа
                                $order_id=\DB::table('orders')->insertGetId([
                                    'service_id' => $service_id,
                                    'client_id' => $d_info->client_id,
                                    'created_at' => Carbon::now()
                                ]);
                                \DB::table('order_statuses')->insert([
                                    'order_id' => $order_id,
                                    'status_id' => 3
                                ]);
                            }

                        //добавление товара
                        $product_order_record=\DB::table('order_products')->insertGetId([
                            'order_id' => $order_id,
                            'product_id' => $d_info->spec_info
                        ]);
                        //переход к следующей стадии
                        $text="Какое количество товара вы хотите добавить в заказ? Напишите число.";
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(6,$from_id,$service_id),['next_stage'=>6,'pre_stage'=>5,'spec_info'=>$product_order_record]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(6,$from_id,$service_id),['next_stage'=>6,'pre_stage'=>5,'spec_info'=>$product_order_record]);
                        }

                        break;
                    case 'back':          //to 3

                        $categories=Category::all();
                        $text='Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер:<br> ';
                        foreach ($categories as $category){
                            $text=$text.$category->id.") ".$category->name.'. <br> ';
                        }
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>5,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>5,'spec_info'=>null]);
                        }
                        break;
                    case 'to_begin':      //to 2
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
                            \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                            $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                        }else{
                            $text="Вы вернулись в главное меню!";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                        }
                        break;
                    case 'cancel_order':  //to 2
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
                            \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                            $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                        }else{
                            $text="Вы вернулись в главное меню!";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                        }
                        break;
                    case 'done_order':    //to 2
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
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->update(['status_id'=>2]);
                            $text="Вы вернулись в главное меню! Формирование заказа завершено, он принят в обработку.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                        }else{
                            $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                        }
                        break;
                    default:
                        $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(5,$from_id,$service_id),['next_stage'=>5,'pre_stage'=>null,'spec_info'=>(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('spec_info','client_id')->get())[0]->spec_info]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(5,$from_id,$service_id),['next_stage'=>5,'pre_stage'=>null,'spec_info'=>(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('spec_info','client_id')->get())[0]->spec_info]);
                        }
                }

                break;

            case 6:             //Ввод количества добавляемого товара
                if($payload == null){ //to 3
                    if (is_numeric($message)) {
                        $dialog_info = (Dialog::where('chat_id', '=', $from_id)->where('service_id', '=', $service_id)->select('client_id', 'spec_info')->get())[0];
                        \DB::table('order_products')->where('id', '=', $dialog_info->spec_info)->update(['amount' => $message]);
                        $categories=Category::all();
                        $text='Товар добавлен в количестве '.$message.'шт. <br> Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер: <br> ';
                        foreach ($categories as $category){
                            $text=$text.$category->id.") ".$category->name.'. <br> ';
                        }
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>6,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>6,'spec_info'=>null]);
                        }
                    }
                }else{
                    switch ($payload){
                        case 'back':          //to 3
                            $categories=Category::all();
                            $text='Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер: <br> ';
                            foreach ($categories as $category){
                                $text=$text.$category->id.") ".$category->name.'. <br> ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>6,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>6,'spec_info'=>null]);
                            }
                            break;
                        case 'to_begin':      //to 2
                            $order=\DB::table('dialogs')
                                ->join('clients','clients.id','=','dialogs.client_id')
                                ->join('orders','clients.id','=','orders.client_id')
                                ->join('order_statuses', 'orders.id','=','order_statuses.order_id')
                                ->select('order_statuses.status_id as status','orders.id as order')
                                ->where('dialogs.chat_id','=',$from_id)
                                ->where('dialogs.service_id','=',$service_id)
                                ->where('order_statuses.status_id', '=' ,3)
                                ->get();
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' =>6]);
                            if(isset($order[0])){
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                            }
                            break;
                        case 'cancel_order':  //to 2
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
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                            }
                            break;
                        case 'done_order':    //to 2
                            $order=\DB::table('dialogs')
                                ->join('clients','clients.id','=','dialogs.client_id')
                                ->join('orders','clients.id','=','orders.client_id')
                                ->join('order_statuses', 'orders.id','=','order_statuses.order_id')
                                ->select('order_statuses.status_id as status','orders.id as order')
                                ->where('dialogs.chat_id','=',$from_id)
                                ->where('dialogs.service_id','=',$service_id)
                                ->where('order_statuses.status_id', '=' ,3)
                                ->get();
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 6]);
                            if(isset($order[0])){
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->update(['status_id'=>2]);
                                $text="Вы вернулись в главное меню! Формирование заказа завершено, он принят в обработку.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                            }
                            break;
                        default:
                            $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(6,$from_id,$service_id),['next_stage'=>6,'pre_stage'=>6,'spec_info'=>(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('spec_info','client_id')->get())[0]->spec_info]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(6,$from_id,$service_id),['next_stage'=>6,'pre_stage'=>6,'spec_info'=>(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('spec_info','client_id')->get())[0]->spec_info]);
                            }
                    }
                }

                break;

            case 7:             //Меню информации
                switch ($payload){
                    case 'order_info':    //to 8
//
                        $client_id=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('client_id')->get())[0]->client_id;
                        $orders=\DB::table('orders')->join('order_statuses','orders.id','=','order_statuses.order_id')
                            ->where('orders.client_id','=',$client_id)->where('orders.service_id','=',$service_id)
                            ->where('order_statuses.status_id','=',2)->select('orders.created_at','order_statuses.updated_at' ,'orders.id')->get();
                        if ($orders->toArray() == []){
                            $text='Список заказов в выполнении пуст. 
                            Пожалуйста, воспользуйтесь кнопкой для возвращения к главному меню!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(12,$from_id,$service_id),['next_stage'=>12,'pre_stage'=>7,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(12,$from_id,$service_id),['next_stage'=>12,'pre_stage'=>7,'spec_info'=>null]);
                            }
                        }else{
                            $text='Введите номер заказа для просмотра содержимого. <br> Заказы в обработке: <br> ';
                            foreach ($orders as $order){
                                $text=$text.$order->id.') Заказ от '.$order->created_at.' выполняется. <br>';
                                if($order->updated_at!=null){
                                    $text=$text.' Последнее изменение от '.$order->updated_at.' <br> ';
                                }
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(8,$from_id,$service_id),['next_stage'=>8,'pre_stage'=>7,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(8,$from_id,$service_id),['next_stage'=>8,'pre_stage'=>7,'spec_info'=>null]);
                            }
                        }

                        break;
                    case 'product_list':  //to 3
                        $categories=Category::all();
                        $text='Выберите категорию из списка и отправите её номер: <br> ';
                        foreach ($categories as $category){
                            $text=$text.$category->id.") ".$category->name.'. <br> ';
                        }
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>7,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>7,'spec_info'=>null]);
                        }
                        break;
                    default:
                        $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>null,'spec_info'=>null]);
                        }
                        if($service_id == 3){
                            sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>null,'spec_info'=>null]);
                        }
                }

                break;

            case 8:             //Вывод действующих заказов
                #order          //to 9
                if($payload == null){ //to 9
                    if (is_numeric($message)) {
                        $client_id = (Dialog::where('chat_id', '=', $from_id)->where('service_id', '=', $service_id)->select('client_id')->get())[0]->client_id;
                        $order_info = Order::find($message);
                        if ($client_id == $order_info->client_id) {
                            if ($order_info != null) {

                                $products = \DB::table('order_products')->join('products', 'products.id', '=', 'order_products.product_id')
                                    ->where('order_id', '=', $message)->select('products.id', 'products.name', 'products.price', 'order_products.amount')->get();
                                $text = 'Введите номер товара из заказа для формирования отзыва. <br> Состав заказа №' . $order_info->id . ' от ' . $order_info->created_at . ': <br> ';
                                foreach ($products as $product) {
                                    $text = $text . $product->id . ') ' . $product->name . ' - ' . $product->price . 'р ' . $product->amount . 'шт. <br> ';
                                }
                                if ($service_id == 2) {
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(9, $from_id, $service_id), ['next_stage' => 9, 'pre_stage' => 8, 'spec_info' => $message]);
                                }
                                if ($service_id == 3) {
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(9, $from_id, $service_id), ['next_stage' => 9, 'pre_stage' => 8, 'spec_info' => $message]);
                                }
                            } else {
                                $text = 'У вас нет такого  заказа! Повторите выбор.';
                                if ($service_id == 2) {
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(8, $from_id, $service_id), ['next_stage' => 8, 'pre_stage' => null, 'spec_info' => null]);
                                }
                                if ($service_id == 3) {
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(8, $from_id, $service_id), ['next_stage' => 8, 'pre_stage' => null, 'spec_info' => null]);
                                }
                            }
                        }else{
                            $text = 'У вас нет такого  заказа! Повторите выбор.';
                            if ($service_id == 2) {
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(8, $from_id, $service_id), ['next_stage' => 8, 'pre_stage' => null, 'spec_info' => null]);
                            }
                            if ($service_id == 3) {
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(8, $from_id, $service_id), ['next_stage' => 8, 'pre_stage' => null, 'spec_info' => null]);
                            }
                        }
                    }
                }else{
                    switch ($payload){
                        case 'back':          //to 7
                            $text='Вы вернулись в меню информации. Выберите тип информации, которую хотите получить, используя варианты на кнопках!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>8,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>8,'spec_info'=>null]);
                            }
                            break;
                        case 'to_begin':      //to 2
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
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>8,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>8,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>8,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>8,'spec_info'=>null]);
                                }
                            }
                            break;
                        default:
                            $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(8,$from_id,$service_id),['next_stage'=>8,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(8,$from_id,$service_id),['next_stage'=>8,'pre_stage'=>null,'spec_info'=>null]);
                            }
                    }
                }

                break;

            case 9:             //Информация по заказу
                #product        //to 10
                if($payload == null){ //to 10
                    if (is_numeric($message)){
                        $product=Product::find($message);
                        if(isset($product->id)){
//
                            $text='Формирование отзыва на '.$product->name.' - '.$product->price.' <br> '.$product->decription.' <br> Отправьте сообщение с текстом отзыва!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(10,$from_id,$service_id),['next_stage'=>10,'pre_stage'=>9,'spec_info'=>$message]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(10,$from_id,$service_id),['next_stage'=>10,'pre_stage'=>9,'spec_info'=>$message]);
                            }
                        }else{
                            $text='Такого товара нет в заказе. Повторите выбор!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(9,$from_id,$service_id),['next_stage'=>9,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(9,$from_id,$service_id),['next_stage'=>9,'pre_stage'=>null,'spec_info'=>null]);
                            }
                        }
                    }
                }else{
                    switch ($payload){
                        case 'back':          //to 8/11
                            $pre_stage=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('pre_stage')->get())[0]->pre_stage;
                            if ($pre_stage == 8){

                                $client_id=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('client_id')->get())[0]->client_id;
                                $orders=\DB::table('orders')->join('order_statuses','orders.id','=','order_statuses.order_id')
                                    ->where('orders.client_id','=',$client_id)->where('orders.service_id','=',$service_id)
                                    ->where('order_statuses.status_id','=',2)->select('orders.created_at','order_statuses.updated_at' ,'orders.id')->get();
                                $text='Заказы в обработке: <br> ';
                                foreach ($orders as $order){
                                    $text=$text.$order->id.') Заказ от '.$order->created_at.' выполняется. <br>';
                                    if($order->updated_at!=null){
                                        $text=$text.' Последнее изменение от '.$order->updated_at.' <br> ';
                                    }
                                }
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(8,$from_id,$service_id),['next_stage'=>8,'pre_stage'=>9,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(8,$from_id,$service_id),['next_stage'=>8,'pre_stage'=>9,'spec_info'=>null]);
                                }
                            }elseif ($pre_stage == 11){

                                $client_id=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('client_id')->get())[0]->client_id;
                                $orders=\DB::table('orders')->join('order_statuses','orders.id','=','order_statuses.order_id')
                                    ->where('orders.client_id','=',$client_id)->where('orders.service_id','=',$service_id)
                                    ->select('orders.created_at','order_statuses.updated_at' ,'orders.id')->get();
                                $text='Список заказов: <br> ';
                                foreach ($orders as $order){
                                    $text=$text.$order->id.') Заказ от '.$order->created_at.' завершен. <br>';
                                    if($order->updated_at!=null){
                                        $text=$text.' Последнее изменение от '.$order->updated_at.' <br> ';
                                    }
                                }
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11,$from_id,$service_id),['next_stage'=>11,'pre_stage'=>9,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(11,$from_id,$service_id),['next_stage'=>11,'pre_stage'=>9,'spec_info'=>null]);
                                }
                            }
                            break;
                        case 'to_begin':      //to 2
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
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>9,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>9,'spec_info'=>null]);
                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>9,'spec_info'=>null]);
                                }
                                if($service_id == 3){
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>9,'spec_info'=>null]);
                                }
                            }
                            break;
                    }
                }

                break;

            case 10:            //Формирование отзыва
                #message        //to 2
                if($payload == null){ //to 10
                        $dialog_info = (Dialog::where('chat_id', '=', $from_id)->where('service_id', '=', $service_id)->select('client_id', 'spec_info')->get())[0];

                            //записываем отзыв
                            \DB::table('product_feedbacks')->insert([
                                'text'=>$message,
                            'client_id'=>$dialog_info->client_id,
                            'product_id'=>$dialog_info->spec_info,
                            'service_id'=>$service_id,
                            'created_at'=>Carbon::now()
                            ]);

                            $text='Отзыв принят в обработку! Вы вернулись в главное меню';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>$message]);
                            }
                            if($service_id == 3){
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>$message]);
                            }

                }elseif ($payload=='cancel'){ //to 2

                    $text="Вы вернулись в главное меню!";
                    if($service_id == 2){
                        sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>null]);
                    }
                    if($service_id == 3){
                        sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>null]);
                    }
                }else{
                    $text='Ошибка!';
                    if($service_id == 2){
                        sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>$message]);
                    }
                    if($service_id == 3){
                        sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>$message]);
                    }
                }

                break;

            case 11:            //Вывод завершенных заказов
                #order          //to 9
                if($payload == null) { //to 9
                    if (is_numeric($message)) {
                        $client_id = (Dialog::where('chat_id', '=', $from_id)->where('service_id', '=', $service_id)->select('client_id')->get())[0]->client_id;
                        $order_info = Order::find($message);
                        if ($client_id == $order_info->client_id) {
                            if ($order_info != null) {

                                $products = \DB::table('order_products')->join('products', 'products.id', '=', 'order_products.product_id')
                                    ->where('order_id', '=', $message)->select('products.id', 'products.name', 'products.price', 'order_products.amount')->get();
                                $text = 'Введите номер товара из заказа для формирования отзыва. <br> Состав заказа №' . $order_info->id . ' от ' . $order_info->created_at . ': <br> ';
                                foreach ($products as $product) {
                                    $text = $text . $product->id . ') ' . $product->name . ' - ' . $product->price . 'р ' . $product->amount . 'шт. <br> ';
                                }
                                if ($service_id == 2) {
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(9, $from_id, $service_id), ['next_stage' => 9, 'pre_stage' => 11, 'spec_info' => $message]);
                                }
                                if ($service_id == 3) {
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(9, $from_id, $service_id), ['next_stage' => 9, 'pre_stage' => 11, 'spec_info' => $message]);
                                }
                            } else {
                                $text = 'У вас нет такого  заказа! Повторите выбор.';
                                if ($service_id == 2) {
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11, $from_id, $service_id), ['next_stage' => 11, 'pre_stage' => null, 'spec_info' => null]);
                                }
                                if ($service_id == 3) {
                                    sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(11, $from_id, $service_id), ['next_stage' => 11, 'pre_stage' => null, 'spec_info' => null]);
                                }
                            }
                        } else {
                            $text = 'У вас нет такого  заказа! Повторите выбор.';
                            if ($service_id == 2) {
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11, $from_id, $service_id), ['next_stage' => 11, 'pre_stage' => null, 'spec_info' => null]);
                            }
                            if ($service_id == 3) {
                                sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(11, $from_id, $service_id), ['next_stage' => 11, 'pre_stage' => null, 'spec_info' => null]);
                            }
                        }
                    }
                }elseif ($payload=='cancel'){ //to 2

                    $text="Вы вернулись в главное меню!";
                    if($service_id == 2){
                        sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>null]);
                    }
                    if($service_id == 3){
                        sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>null]);
                    }
                }
                break;
            case 12: //to 2
                if ($payload=='to_begin'){
                    $text="Вы вернулись в главное меню!";
                    if($service_id == 2){
                        sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>12,'spec_info'=>null]);
                    }
                    if($service_id == 3){
                        sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>12,'spec_info'=>null]);
                    }
                }else{
                    $text="Пожалуйста, воспользуйтесь кнопкой для возвращения к главному меню!";
                    if($service_id == 2){
                        sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(12,$from_id,$service_id),['next_stage'=>12,'pre_stage'=>12,'spec_info'=>null]);
                    }
                    if($service_id == 3){
                        sendToTlgrmJob::dispatch($from_id, $text, null, telegramController::makeKeyboardTlgrm(12,$from_id,$service_id),['next_stage'=>12,'pre_stage'=>12,'spec_info'=>null]);
                    }
                }
                break;
        }

    }
}
