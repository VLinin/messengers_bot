<?php

//views
use App\Client;
use App\Dialog_stage;

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



Route::get('test',function (){
    $dialog_query=\DB::table('dialogs')
        ->where('dialogs.chat_id','=',456)
        ->where('dialogs.service_id','=',2)
        ->select('dialogs.id','dialogs.client_id as client','dialogs.dialog_stage_id as stage')
        ->get();
    dump(isset($dialog_query[0]->client));
});




