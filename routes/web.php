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
Route::get('/uploadVKphoto', 'vkController@uploadPhotosToVk');
Route::post('/vk','vkController@index');
Route::post('/tlgrm','telergramController@index');

Route::get('/androidAuth','androidController@auth');
Route::get('/androidOrders','androidController@orders');
Route::get('/androidProducts','androidController@products');

//Route::get('/vktest',function () {
//    dump(\App\Client::all());
//    dump(\App\Dialog::all());
//    dump(\App\Image::all());
//});
//
//Route::get('/mage',function () {
//    $img=\App\Image::all();
//    foreach ($img as $i){
//        dump($i->path." - ".$i->vk);
//    }
//});
//
//
//
//
//Route::get('/test',function () {
//    $orders=\DB::table('orders')->join('order_statuses','orders.id','=','order_statuses.order_id')
//        ->where('orders.client_id','=',1)->where('orders.service_id','=',2)
//        ->where('order_statuses.status_id','=',2)->select('orders.created_at','order_statuses.updated_at' ,'orders.id')->get();
//    dump(isset($orders[0]));
//
//});




