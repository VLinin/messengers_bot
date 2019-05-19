<?php

//views
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



//Route::get('test',function (){
//    $query=$data=DB::table('orders')
//        ->join('services','orders.service_id','=','services.id')
//        ->whereBetween('created_at', ['2019-05-01', '2019-05-25'])
//        ->select(DB::raw('count(orders.id) as count, services.name'))
//        ->groupBy('services.name')
//        ->get();
//    $data=[
//        ['Платформа', 'Количество заказов'],
//    ];
//    foreach ($query as $item){
//        $data[]=[$item->name,$item->count];
//    }
//    dump($data);
//});




