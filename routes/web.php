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

Route::post('/dwnldExcel','actionController@makeExcel');

//services

Route::post('/vk','vkController@index');
Route::post('/tlgrm','telergramController@index');

Route::get('/androidAuth','androidController@auth');
Route::get('/androidOrders','androidController@orders');
Route::get('/androidProducts','androidController@products');

Route::get('/vktest',function () {
    dump(\App\Client::all());
    dump(\App\Dialog::all());
});

Route::get('/test',function () {

    $url = 'https://api.vk.com/method/photos.getUploadServer';
    $user_token="365c7ec1ffc49087c9d4bb749e563b5cc7ea8552649ed52c4c7385848684ba6f229099b09adf306764169";
    $group_id="182538296"; //id группы вк
    $alb_id="264876553";
    //получаем адресс сервиса для загрузки фото
    $params = array(
        "group_id" => $group_id,
        "album_id" => $alb_id,
        'access_token' => $user_token,
        'v' => '5.95',
    );
    // В $result вернется id отправленного сообщения
    $result = file_get_contents($url, false, stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($params)
        )
    )));
    $server_url=json_decode($result)->response->upload_url;
    //загружаем фото
    try {
        $cfile = curl_file_create(\Storage::disk('public')->path('pr8.png'), 'image/png', 'temp.png');
        dump($cfile);
    } catch (FileNotFoundException $e) {
        return null;
    }
    $ch = curl_init($server_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array("file" => $cfile));
    $result = json_decode(curl_exec($ch),true);
    curl_close($ch);
    //сохраняем фото
    $url = 'https://api.vk.com/method/photos.save';
    $params = array(
        "group_id"=>$group_id,
        "album_id"=>$alb_id,
        "photos_list"=>$result['photos_list'],
        "server"=>$result['server'],
        "hash"=>$result['hash'],
        'access_token' => $user_token,  // access_token можно вбить хардкодом, если работа будет идти из под одного юзера
        'v' => '5.95',
    );
    $result = file_get_contents($url, false, stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($params)
        )
    )));
    $photo_id=json_decode($result)->response[0]->id;
    dump($result);
    dump($photo_id);

});




