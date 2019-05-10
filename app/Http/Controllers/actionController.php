<?php

namespace App\Http\Controllers;

use App\Distribution;
use App\Product_feedback;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TheSeer\Tokenizer\Token;

class actionController extends Controller
{

    public function checkFeedback(Request $request){
        $id=$request->input('btn');
        $pr_f=new Product_feedback();
        $pr_f->check($id);
        return view('feedback');
    }

    public function sendFeedback(Request $request){
        $fio=$request->input('fio');
        $text=$request->input('text');
        $service_id=$request->input('service_id');
        switch ($service_id){
            case 1:
                callmethod($fio,$text);
                break;
            case 2:
                break;
        }
        return view('feedback');
    }
    public function addDistribution(Request $request){
        $d = Distribution::create(['text' => $request->input('text'),'run_date'=>$request->input('date')]);
        //обработка изображения

        //сервисы

        return view('distributions');
    }
    public function cancelDistribution(Request $request){
        Distribution::find($request->input('btn'))->delete();
        return view('distributions');
    }
    public function chngToken(Request $request){
        $serv=new Service();
        $serv->changeToken($request->input('id'),$request->input('text'));
        return view('tokens');
    }

}
