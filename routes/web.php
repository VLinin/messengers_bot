<?php

//views

use App\Dialog;
use App\Dialog_stage;
use App\Image;
use App\Product;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use VK\Client\VKApiClient;

Route::get('/', function () {
    return view('start');
})->name('start');

Route::get('/distributions', function () {
    return view('distributions');
})->name('distributions');

Route::get('/listDistributions', function () {
    return view('listDistributions');
})->name('listDistributions');

Route::get('/statistics', function () {
    return view('statistics');
})->name('statistics');

Route::get('/feedback', function () {
    return view('feedback');
})->name('feedback');

Route::get('/tokens', function () {
    return view('tokens');
})->name('tokens');

Route::get('/feedback_answ',function(){
    return view('feedback_answ');
});

Route::get('/getStatisticsData/{bd}/{ed}',function($bd,$ed){
    $data=\App\Http\Controllers\actionController::getStatData($bd,$ed);
    return $data;
});

//actions

Route::post('/checkFeedback','actionController@checkFeedback');

Route::post('/sendFeedback','actionController@sendFeedback');

Route::post('/addDistribution', 'actionController@addDistribution');

Route::post('/cancelDistribution','actionController@cancelDistribution');

Route::post('/chngToken','actionController@chngToken');


//services
Route::get('/uploadVKphoto', 'vkController@uploadPhotosToVk');
Route::post('/vk','vkController@index');
Route::post('/tlgrm','telegramController@index');

Route::get('/androidAuth','androidController@auth');
Route::get('/androidOrders','androidController@orders');
Route::get('/androidProducts','androidController@products');

Route::get('/vkClient',function () {
    dump(\App\Client::all());
    dump(\App\Dialog::all());
    dump(\App\Image::all());
});
Route::get('/vkDialog',function () {
    dump(\App\Dialog::all());
});
Route::get('/vkOrder',function () {
    dump(\App\Order::all());
});

Route::get('/test',function () {
    $proxy='64.118.88.39:19485';

    $keyboard = array(
        array("[Destaques]","[Campinas e RMC]","[esportes]"),
        array("[Destaques2]","[Campinas e RMC2]","[esportes2]"),
    );
    $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
    $reply = json_encode($resp);

    $inline_button1 = array("text"=>"Google url","callback_data"=>"http://google.com");
    $inline_button2 = array("text"=>"work plz","callback_data"=>'/plz');
    $inline_keyboard = [[$inline_button1,$inline_button2]];
    $keyboard=array("inline_keyboard"=>$inline_keyboard,"inline_keyboard"=>$inline_keyboard);
    $replyMarkup = json_encode($keyboard);
        $response = array(
            'chat_id' =>  331906939,
            'text' => 'is test',
            'reply_markup' => \App\Http\Controllers\telegramController::makeKeyboardTlgrm(7,331906939,3)
        );


    $ch = curl_init();
    $url = 'https://api.telegram.org/bot' . '845701278:AAG-eaVtv4oNOjhYOSHGaNU6DPvb-ml3P2k' . '/sendMessage';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PROXY, "socks5://$proxy");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($response));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    dump($result);

});




