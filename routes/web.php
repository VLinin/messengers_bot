<?php

//views

use App\Dialog_stage;
use App\Image;
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



Route::get('/test',function () {
    $vk = new VKApiClient();
    $vk->messages()->send("34743dbbc8c9d33dbde7ea6394b98800fe168dab289a443e5a0f2b4e297a340b490d04f9447312a4c9913",
        [
            'random_id' => random_int(0,234456),
            'peer_id' => 194004680,
            'message' => "Для взаимодействия с системой укажите свой мобильный телефон начиная с 8.... 
                        Это позволит связать ваши аккаунты из различных сервисов и осуществлять заказы!",
            'v' => '5.95',
            'keyboard' => json_encode(\App\Http\Controllers\vkController::makeKeyboardVK(1,194004680,2), JSON_UNESCAPED_UNICODE),
        ]);
});




