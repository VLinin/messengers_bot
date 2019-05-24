<?php

namespace App\Http\Controllers;

use App\Dialog;
use App\Distribution;
use App\Jobs\sendToVKJob;
use App\Product_feedback;
use App\Service;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use TheSeer\Tokenizer\Token;

class actionController extends Controller
{

    public function checkFeedback(Request $request){
        $id=$request->input('id');
        $pr_f=new Product_feedback();
        $pr_f->check($id);
        return view('feedback');
    }

    ///////////////////////////////////////////////////////////////////////////////////with services
    public function sendFeedback(Request $request){
        $fio=$request->post('fio');
        $client_id=$request->post('client_id');
        $text=$request->post('text');
        $service_id=$request->post('service_id');
        switch ($service_id){
            case 2:
                $dialog=Dialog::where('client_id','=', $client_id)->where('service_id','=',$service_id)->select('chat_id','dialog_stage_id','pre_stage','spec_info')->get();
                dump($dialog);
//                $url = 'https://api.vk.com/method/messages.send';
//                $params = array(
//                    'random_id' => random_int(0,234456),
//                    'peer_id' => $dialog->chat_id,    // Кому отправляем
//                    'message' =>$text,   // Что отправляем
//                    'access_token' => "34743dbbc8c9d33dbde7ea6394b98800fe168dab289a443e5a0f2b4e297a340b490d04f9447312a4c9913",
////                    'keyboard' => json_encode(vkController::makeKeyboardVK(1, $dialog->chat_id, $service_id), JSON_UNESCAPED_UNICODE),
//                    'v' => '5.95',
//                );
//
//                // В $result вернется id отправленного сообщения
//                $result = file_get_contents($url, false, stream_context_create(array(
//                    'http' => array(
//                        'method'  => 'POST',
//                        'header'  => 'Content-type: application/x-www-form-urlencoded',
//                        'content' => http_build_query($params)
//                    )
//                )));
//                sendToVKJob::dispatch($dialog->chat_id, $text, null, vkController::makeKeyboardVK($dialog->dialog_stage_id, $dialog->chat_id, $service_id),['next_stage'=>$dialog->dialog_stage_id,'pre_stage'=>$dialog->pre_stage,'spec_info'=>$dialog->spec_info]);
//                \DB::table('product_feedbacks')->where('id',$request->post('feedback_id'))->update(['checked'=>1]);
                break;
            case 3:

//                \DB::table('product_feedbacks')->where('id',$request->post('feedback_id'))->update(['checked'=>1]);
                break;
        }
        return view('feedback');
    }


    public function addDistribution(Request $request){

        if($request->post('gridCheck1')=="on" || $request->post('gridCheck2')=="on"){
            $distId=DB::table('distributions')->insertGetId([
                'text' => $request->post('text'),
                'run_date' => $request->post('date')
            ]);

            //обработка изображения
            if($request->file('image')!==null){
                $path = $request->file('image')->store('distributions');

                $imageId=DB::table('images')->insertGetId([
                    'path' => $path
                ]);

                DB::table('image_distributions')->insert([
                    'distribution_id' =>$distId,
                    'image_id' => $imageId
                ]);
            }


            //сервисы
            if ($request->post('gridCheck1')=="on"){
                DB::table('distribution_services')->insert([
                    'distribution_id' => $distId,
                    'service_id' => 2
                ]);
            }

            if ($request->post('gridCheck2')=="on"){
                DB::table('distribution_services')->insert([
                    'distribution_id' => $distId,
                    'service_id' => 3
                ]);
            }
            return view('listDistributions');
        }else{
            return view('distributions');
        }

    }

    public function cancelDistribution(Request $request){
        $id=$request->post('btn');
        DB::table('distribution_services')->where('distribution_id','=',$id)->delete();
        $imageId=DB::table('image_distributions')->where('distribution_id','=',$id)->select('image_id')->get();
        DB::table('image_distributions')->where('distribution_id','=',$id)->delete();
        $path=DB::table('images')->where('id','=',$imageId[0]->image_id)->select('path')->get();
        DB::table('images')->where('id','=',$imageId[0]->image_id)->delete();
        Storage::delete($path[0]->path);
        Distribution::find($id)->delete();
        return view('listDistributions');
    }

    public function chngToken(Request $request){
        $serv=new Service();
        $serv->changeToken($request->post('id'),$request->post('text'));
        return view('tokens');
    }

    public static function getStatData($bd, $ed){

        $query=DB::table('orders')
            ->join('services','orders.service_id','=','services.id')
            ->whereBetween('created_at', [$bd, Carbon::createFromDate($ed)->add(1,'day')])
            ->select(DB::raw('count(orders.id) as count, services.name'))
            ->groupBy('services.name')
            ->get();
        foreach ($query as $item){
            $data[]=[$item->name,$item->count];
        }
        return json_encode($data);
    }

}
