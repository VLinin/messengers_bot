<?php

namespace App\Http\Controllers;

use App\Category;
use App\Client;
use App\Dialog;
use App\Dialog_stage;
use App\Jobs\sendToVKJob;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VK\Client\VKApiClient;

class serviceController extends Controller
{

    public static function stageProcess($stage, $data, $service_id){
        if($service_id == 2){
            if(isset($data->object->payload['do'])){
                $payload = $data->object->payload['do'];
            }else{
                $payload=null;
            }
            $message = $data->object->text;
        }
        if($service_id == 3){
            $payload= null;
            $message = null;
        }
        $from_id=$data->object->from_id;

        switch ($stage){
            case 1:             //Начальная стадия
                //to 2 stage
                Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2]);
                $text="Приступим к взаимодействию! \n
                        В этом чате можно осуществлять заказы, отправлять отзывы и получать интересную информацию!
                        Для работы используйте кнопки с предоставленными вариантами.
                      ";
                if($service_id == 2){
                    sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2));
                }
                if($service_id == 3){

                }
                break;

            case 2:             //Основное меню
                switch ($payload){
                    case 'make_order':    //to 3
                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 3]);
                        $categories=Category::all();
                        $text='Выберите категорию из списка и отправите её номер: \n ';
                        foreach ($categories as $category){
                            $text=$text.$category->id.") ".$category->name.'. \n ';
                        }
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3));
                        }
                        if($service_id == 3){

                        }
                        break;
                    case 'get_info':      //to 7
                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 7]);
                        $text='Выберите тип информации, которую хотите получить, использя варианты на кнопках!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(7));
                        }
                        if($service_id == 3){

                        }
                        break;
                    case 'send_feedback': //to 11
                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 11]);
                        $text='Выберите тип информации, которую хотите получить, использя варианты на кнопках!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11));
                        }
                        if($service_id == 3){

                        }
                        break;
                    default:
                        $text='Не знаю как реагировать! Используй кнопки или ознакомься с сообщениями выше, так мы точно сможем договориться!';
                        if($service_id == 2){
                            sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2));
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
                            Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 4]);
                            $text='Выберите товар из списка и отправьте его номер: \n ';
                            $products=Product::all()->where('category_id','=',$category->id);
                            foreach ($products as $product){
                                $text=$text.$product->id.") ".$product->name.'. - '.$product->price.'р. \n ';
                            }
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(11));
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text='Такой категории у нас нет. Повторите выбор!';
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(3));
                            }
                            if($service_id == 3){

                            }
                        }
                    }
                }
                switch ($payload){
                    case 'to_begin': //to 2
                        $order=\DB::table('dialogs')
                            ->join('clients','clients.id','=','dialogs.client_id')
                            ->join('orders','clients.id','=','orders.client_id')
                            ->join('order_statuses', 'orders.id','=','order_statuses.order_id')
                            ->select('order_statuses.status_id as status','orders.id as order')
                            ->where('dialogs.chat_id','=',456)
                            ->where('dialogs.service_id','=',2)
                            ->where('order_statuses.status_id', '=' ,3)
                            ->get();
                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2]);
                        if(isset($order[0])){
                            \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                            $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2));
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text="Вы вернулись в главное меню!";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2));
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
                            ->where('dialogs.chat_id','=',456)
                            ->where('dialogs.service_id','=',2)
                            ->where('order_statuses.status_id', '=' ,3)
                            ->get();
                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2]);
                        if(isset($order[0])){
                            \DB::table('order_products')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->delete();
                            \DB::table('orders')->where('id','=',$order[0]->order)->delete();
                            $text="Вы вернулись в главное меню! Формирование заказа отменено.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2));
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text="Вы вернулись в главное меню!";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2));
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
                            ->where('dialogs.chat_id','=',456)
                            ->where('dialogs.service_id','=',2)
                            ->where('order_statuses.status_id', '=' ,3)
                            ->get();
                        Dialog::where('chat_id','=',$from_id)->where('service_id','=',$service_id)->update(['dialog_stage_id' => 2]);
                        if(isset($order[0])){
                            \DB::table('order_statuses')->where('order_id','=',$order[0]->order)->update(['status_id'=>1]);
                            $text="Вы вернулись в главное меню! Формирование заказа завершено, он принят в обработку.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2));
                            }
                            if($service_id == 3){

                            }
                        }else{
                            $text="Вы вернулись в главное меню! При формировании заказа возникла ошибка, нам очень жаль.";
                            if($service_id == 2){
                                sendToVKJob::dispatch($from_id, $text, null, vkController::makeKeyboardVK(2));
                            }
                            if($service_id == 3){

                            }
                        }
                        break;
                }
                break;

            case 4:             //Выбор товара из категории
                #product        //to 5
                'back'          //to 3
                'to_begin'      //to 2
                if( product in order)
                'cancel_order'  //to 2
                'done_order'    //to 2
                break;

            case 5:             //Информация о товаре
                'add_to_order'  //to 6
                'back'          //to 4
                'to_begin'      //to 2
                if( product in order)
                'cancel_order'  //to 2
                'done_order'    //to 2
                break;

            case 6:             //Ввод количества добавляемого товара
                #kol-vo product //to 4
                'back'          //to 5
                'to_begin'      //to 2
                if( product in order)
                'cancel_order'  //to 2
                'done_order'    //to 2
                break;

            case 7:             //Меню информации
                'order_info'    //to 8
                'product_list'  //to 3
                break;

            case 8:             //Вывод действующих заказов
                #order          //to 9
                'back'          //to 7
                'to_begin'      //to 2
                break;

            case 9:             //Информация по заказу
                #product        //to 10
                'back'          //to 8
                'to_begin'      //to 2
                break;

            case 10:            //Формирование отзыва
                #message        //to 2
                'cancel'        //to 2
                break;

            case 11:            //Вывод завершенных заказов
                #order          //to 9
                'cancel'        //to 2
                break;
        }

    }
}
