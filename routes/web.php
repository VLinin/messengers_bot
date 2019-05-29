<?php

//views

use App\Dialog;
use App\Dialog_stage;
use App\Distribution;
use App\Image;
use App\Product;
use Carbon\Carbon;
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
    $distributions = Distribution::where('run_date','<=',Carbon::now())->get();
    $services=DB::table('distribution_services')->where('distribution_id','=',1)->get();
    $d=Dialog::where('service_id','=',2)->get();
    dump($distributions);
    dump($services);
    dump($d);
});




