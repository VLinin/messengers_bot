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
//    $proxy='64.118.88.39:19485';
//    $response = array(
//        'chat_id' => 331906939,
//        "caption" => "\"Ты мое счастье\" испанский мармелад в банке, 210 гр. - 290р
//
//Вместо тысячи слов дарят Рафаэлло.
//А вместо миллиона эмоций - настоящую концентрированную любовь в одной баночке!
//И это наш мармелад с пожеланиями на любой случай!
//\"Ты мое счастье\"- простые три слова, содержащие огромную нежность.
//А внутри- испанский вкуснейший мармелад!",
//        'photo' => curl_file_create(\Storage::disk('public')->path('pr1.png'), 'image/png', 'temp.png')
//    );
//
//    $ch = curl_init();
//    $url = 'https://api.telegram.org/bot' . '845701278:AAG-eaVtv4oNOjhYOSHGaNU6DPvb-ml3P2k' . '/sendPhoto';
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//        "Content-Type:multipart/form-data"
//    ));
//    curl_setopt($ch, CURLOPT_PROXY, "socks5://$proxy");
//    curl_setopt($ch, CURLOPT_HEADER, false);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_POST, 1);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, ($response));
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    $result = curl_exec($ch);
});




