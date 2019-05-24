<?php

namespace App\Http\Controllers;

use App\Category;
use App\Client;
use App\Dialog;
use App\Dialog_stage;
use App\Jobs\sendToVKJob;
use App\Order;
use App\Product;
use App\Product_feedback;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VK\Client\VKApiClient;

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
        if($service_id == 3){
            $payload= null;
            $message = null;
            $from_id=null;
        }


        switch ($stage){
            case 1:             //Начальная стадия
                //to 2 stage
//                Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 1]);
                $text="Приступим к взаимодействию! <br>
                        В этом чате можно осуществлять заказы, отправлять отзывы и получать интересную информацию!
                        Для работы используйте кнопки с предоставленными вариантами.
                      ";
                if($service_id == 2){
                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>1,'spec_info'=>null]);
                }
                if($service_id == 3){

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

                        }
                        break;
                    case 'get_info':      //to 7

                        $text='Выберите тип информации, которую хотите получить, используя варианты на кнопках!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>2,'spec_info'=>null]);
                        }
                        if($service_id == 3){

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

                            }
                        }else {
                            $text = 'Список завершенных заказов: <br> ';
                            foreach ($orders as $order) {
                                $text = $text . $order->id . ') Заказ от ' . $order->created_at . ' завершен. <br>';
                                if ($order->updated_at != null) {
                                    $text = $text . ' Последнее изменение от ' . $order->updated_at . ' <br> ';
                                }
                            }
                            if ($service_id == 2) {
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11, $from_id, $service_id), ['next_stage' => 11, 'pre_stage' => 2, 'spec_info' => null]);
                            }
                            if ($service_id == 3) {

                            }
                        }
                        break;
                    default:
                        $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>null,'spec_info'=>null]);
                        }
                        if($service_id == 3){

                        }
                }
                break;

            case 3:             //Выбор категории
                if($payload == null){ //to 4
                    if (is_numeric($message)){
                        $category=Category::find($message);
                        if(isset($category->id)){
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 4, 'pre_stage' => 3,'spec_info' => $category->id]);
                            $text='Выберите товар из списка и отправьте его номер: <br> ';
                            $products=Product::all()->where('category_id','=',$category->id);
                            foreach ($products as $product){
                                $text=$text.$product->id.") ".$product->name.'. - '.$product->price.'р. <br> ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11,$from_id,$service_id),['next_stage'=>4,'pre_stage'=>3,'spec_info'=>$category->id]);
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text='Такой категории у нас нет. Повторите выбор!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 3]);
                            if(isset($order[0])){
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 3]);
                            if(isset($order[0])){
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 3]);
                            if(isset($order[0])){
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->update(['status_id'=>2]);
                                $text="Вы вернулись в главное меню! Формирование заказа завершено, он принят в обработку.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>3,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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

                            $image_path=(\DB::table('images')->join('image_products','image_products.image_id','=','images.id')
                                ->where('image_products.product_id','=',$message)->get())[0]->path;
                            $text=$product->name.' - '.$product->price.'р <br> '.$product->description;
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, $image_path, vkController::makeKeyboardVK(5,$from_id,$service_id),['next_stage'=>5,'pre_stage'=>4,'spec_info'=>$message]);
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text='Такого товара у нас нет. Повторите выбор!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(4,$from_id,$service_id),['next_stage'=>4,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                        }
                    }
                }else{
                    switch ($payload){
                        case 'back':          //to 3
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 3, 'pre_stage' => 4]);
                            $categories=Category::all();
                            $text='Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер: <br> ';
                            foreach ($categories as $category){
                                $text=$text.$category->id.") ".$category->name.'. <br> ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>4,'spec_info'=>null]);
                            }
                            if($service_id == 3){

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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 4]);
                            if(isset($order[0])){
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 4]);
                            if(isset($order[0])){
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 4]);
                            if(isset($order[0])){
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->update(['status_id'=>2]);
                                $text="Вы вернулись в главное меню! Формирование заказа завершено, он принят в обработку.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>4,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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
                        //добавление товара
                        $product_order_record=\DB::table('order_products')->insertGetId([
                            'order_id' => $order_id,
                            'product_id' => $d_info->spec_info
                        ]);
                        //переход к следующей стадии
//                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 6, 'pre_stage' => 5, 'spec_info' => $product_order_record]);
                        $text="Какое количество товара вы хотите добавить в заказ? Напишите число.";
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(6,$from_id,$service_id),['next_stage'=>6,'pre_stage'=>5,'spec_info'=>$product_order_record]);
                        }
                        if($service_id == 3){

                        }

                        break;
                    case 'back':          //to 3
//                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 3, 'pre_stage' => 5]);
                        $categories=Category::all();
                        $text='Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер:<br> ';
                        foreach ($categories as $category){
                            $text=$text.$category->id.") ".$category->name.'. <br> ';
                        }
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>5,'spec_info'=>null]);
                        }
                        if($service_id == 3){

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
//                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' =>5]);
                        if(isset($order[0])){
                            \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                            $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text="Вы вернулись в главное меню!";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){

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
//                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 5]);
                        if(isset($order[0])){
                            \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                            $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text="Вы вернулись в главное меню!";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){

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
//                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 5]);
                        if(isset($order[0])){
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->update(['status_id'=>2]);
                            $text="Вы вернулись в главное меню! Формирование заказа завершено, он принят в обработку.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>5,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                        }
                        break;
                    default:
                        $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(5,$from_id,$service_id),['next_stage'=>5,'pre_stage'=>null,'spec_info'=>null]);
                        }
                        if($service_id == 3){

                        }
                }

                break;

            case 6:             //Ввод количества добавляемого товара
                if($payload == null){ //to 3
                    if (is_numeric($message)) {
                        $dialog_info = (Dialog::where('chat_id', '=', $from_id)->where('service_id', '=', $service_id)->select('client_id', 'spec_info')->get())[0];
                        \DB::table('order_products')->where('id', '=', $dialog_info->spec_info)->update(['amount' => $message]);
//                        Dialog::where('chat_id', '=', $from_id)->where('service_id', '=', $service_id)->update(['dialog_stage_id' => 3, 'pre_stage' => 6]);
                        $categories=Category::all();
                        $text='Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер: <br> ';
                        foreach ($categories as $category){
                            $text=$text.$category->id.") ".$category->name.'. <br> ';
                        }
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>6,'spec_info'=>null]);
                        }
                        if($service_id == 3){

                        }
                    }
                }else{
                    switch ($payload){
                        case 'back':          //to 3
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 3, 'pre_stage' => 6]);
                            $categories=Category::all();
                            $text='Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер: <br> ';
                            foreach ($categories as $category){
                                $text=$text.$category->id.") ".$category->name.'. <br> ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>6,'spec_info'=>null]);
                            }
                            if($service_id == 3){

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

                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' => 6]);
                            if(isset($order[0])){
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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

                                }
                            }else{
                                $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>6,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }
                            break;
                        default:
                            $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(6,$from_id,$service_id),['next_stage'=>6,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                    }
                }

                break;

            case 7:             //Меню информации
                switch ($payload){
                    case 'order_info':    //to 8
//                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 8, 'pre_stage' => 7]);
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

                            }
                        }else{
                            $text='Заказы в обработке: <br> ';
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

                            }
                        }

                        break;
                    case 'product_list':  //to 3
//                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 3, 'pre_stage' => 7]);
                        $categories=Category::all();
                        $text='Вы вернулись к выбору категории. Выберите категорию из списка и отправите её номер: <br> ';
                        foreach ($categories as $category){
                            $text=$text.$category->id.") ".$category->name.'. <br> ';
                        }
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3,$from_id,$service_id),['next_stage'=>3,'pre_stage'=>7,'spec_info'=>null]);
                        }
                        if($service_id == 3){

                        }
                        break;
                    default:
                        $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>null,'spec_info'=>null]);
                        }
                        if($service_id == 3){

                        }
                }

                break;

            case 8:             //Вывод действующих заказов
                #order          //to 9
                if($payload == null){ //to 9
                    if (is_numeric($message)) {
                        $order_info=Order::find($message);
                        if($order_info!=null){

                            $products=\DB::table('order_products')->join('products','products.id','=','order_products.product_id')
                                ->where('order_id', '=', 1)->select('products.id','products.name', 'products.price','order_products.amount')->get();
                            $text='Введите номер товара из заказа для формирования отзыва. <br> Состав заказа №'.$order_info->id.' от '.$order_info->created_at.': <br> ';
                            foreach ($products as $product){
                                $text=$text.$product->id.') '.$product->name.' - '.$product->price.'р '.$product->amount.'шт. <br> ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(9,$from_id,$service_id),['next_stage'=>9,'pre_stage'=>8,'spec_info'=>$message]);
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text='У вас нет такого  заказа! Повторите выбор.';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(8,$from_id,$service_id),['next_stage'=>8,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                        }
                    }
                }else{
                    switch ($payload){
                        case 'back':          //to 7
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 7, 'pre_stage' => 8]);
                            $text='Вы вернулись в меню информации. Выберите тип информации, которую хотите получить, используя варианты на кнопках!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(7,$from_id,$service_id),['next_stage'=>7,'pre_stage'=>8,'spec_info'=>null]);
                            }
                            if($service_id == 3){

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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' =>8]);
                            if(isset($order[0])){
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>8,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>8,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }
                            break;
                        default:
                            $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(8,$from_id,$service_id),['next_stage'=>8,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){

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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 10, 'pre_stage' => 9, 'spec_info' => $message]);
                            $text='Формирование отзыва на '.$product->name.' - '.$product->price.' <br> '.$product->decription.' <br> Отпрвьте сообщение с текстом отзыва!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(10,$from_id,$service_id),['next_stage'=>10,'pre_stage'=>9,'spec_info'=>$message]);
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text='Такого товара нет в заказе. Повторите выбор!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(9,$from_id,$service_id),['next_stage'=>9,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                        }
                    }
                }else{
                    switch ($payload){
                        case 'back':          //to 8/11
                            $pre_stage=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('pre_stage')->get())[0]->pre_stage;
                            if ($pre_stage == 8){
//                                Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 8, 'pre_stage' => 7]);
                                $client_id=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('client_id')->get())[0];
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

                                }
                            }elseif ($pre_stage == 11){
//                                Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 11, 'pre_stage' => 2]);
                                $client_id=(Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->select('client_id')->get())[0];
                                $orders=\DB::table('orders')->join('order_statuses','orders.id','=','order_statuses.order_id')
                                    ->where('orders.client_id','=',$client_id)->where('orders.service_id','=',$service_id)
                                    ->where('order_statuses.status_id','=',1)->select('orders.created_at','order_statuses.updated_at' ,'orders.id')->get();
                                $text='Список завершенных заказов: <br> ';
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
//                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' =>9]);
                            if(isset($order[0])){
                                \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                                \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                                $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>9,'spec_info'=>null]);
                                }
                                if($service_id == 3){

                                }
                            }else{
                                $text="Вы вернулись в главное меню!";
                                if($service_id == 2){
                                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>9,'spec_info'=>null]);
                                }
                                if($service_id == 3){

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
                            $product_feedback= new Product_feedback();
                            $product_feedback->client_id=$dialog_info->client_id;
                            $product_feedback->product_id=$dialog_info->spec_info;
                            $product_feedback->service_id=$service_id;
                            $product_feedback->text_id=$message;
                            $product_feedback->save();

                            $text='Отзыв принят в обработку! Вы вернулись в главное меню';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>$message]);
                            }
                            if($service_id == 3){

                            }

                }elseif ($payload=='cancel'){ //to 2
//                    Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' =>10]);
                    $text="Вы вернулись в главное меню!";
                    if($service_id == 2){
                        sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>null]);
                    }
                    if($service_id == 3){

                    }
                }

                break;

            case 11:            //Вывод завершенных заказов
                #order          //to 9
                if($payload == null){ //to 9
                    if (is_numeric($message)) {
                        $order_info=Order::find($message);
                        if($order_info!=null){
//                            Dialog::where('chat_id', '=', $from_id)->where('service_id', '=', $service_id)->update(['dialog_stage_id' => 9, 'pre_stage' => 11, 'spec_info' => $message]);
                            $products=\DB::table('order_products')->join('products','products.id','=','order_products.product_id')
                                ->where('order_id', '=', 1)->select('products.id','products.name', 'products.price','order_products.amount')->get();
                            $text='Введите номер товара из заказа для формирования отзыва. <br> Состав заказа №'.$order_info->id.' от '.$order_info->created_at.': <br> ';
                            foreach ($products as $product){
                                $text=$text.$product->id.') '.$product->name.' - '.$product->price.'р '.$product->amount.'шт. <br> ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(9,$from_id,$service_id),['next_stage'=>9,'pre_stage'=>11,'spec_info'=>$message]);
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text='У вас нет такого  заказа! Повторите выбор.';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11,$from_id,$service_id),['next_stage'=>11,'pre_stage'=>null,'spec_info'=>null]);
                            }
                            if($service_id == 3){

                            }
                        }
                    }
                }elseif ($payload=='cancel'){ //to 2
//                    Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2, 'pre_stage' =>10]);
                    $text="Вы вернулись в главное меню!";
                    if($service_id == 2){
                        sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2,$from_id,$service_id),['next_stage'=>2,'pre_stage'=>10,'spec_info'=>null]);
                    }
                    if($service_id == 3){

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

                    }
                }else{
                    $text="Пожалуйста, воспользуйтесь кнопкой для возвращения к главному меню!";
                    if($service_id == 2){
                        sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(12,$from_id,$service_id),['next_stage'=>12,'pre_stage'=>12,'spec_info'=>null]);
                    }
                    if($service_id == 3){

                    }
                }
                break;
        }

    }
}
