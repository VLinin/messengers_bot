@extends('layouts.app')
@section('content')
    @php
    if(isset($_POST['btn'])){
           $id= $_POST['btn'];
           $info=(new \App\Product_feedback())->getInfoToAnswer($id);
    }
    @endphp
    <h1>Формирование ответа на отзыв</h1>
    <form method="post" action="sendFeedback">
        <div class="form-group">
            <label for="fio">ФИО клиента</label>
            <input type="text" class="form-control" id="fio" readonly value="{{$info->fio}}">
        </div>
        <div class="form-group">
            <label for="feedback">Текст отзыва клиента</label>
            <textarea class="form-control" id="feedback" rows="4" readonly>{{$info->text}}</textarea>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="service_id" readonly value="{{$info->service->id}}" hidden>
        </div>
        <div class="form-group">
            <label for="text">Текст ответа</label>
            <textarea class="form-control" id="text" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>

@endsection