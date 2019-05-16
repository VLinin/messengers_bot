@extends('layouts.app')
@section('content')
    @php
    if(isset($_GET['id'])){
           $id= $_GET['id'];
           $info=(new \App\Product_feedback())->getInfoToAnswer($id);
    }
    @endphp
    <h1>Формирование ответа на отзыв</h1>
    <form method="post" action="sendFeedback">
        <div class="form-group">
            <label for="fio">ФИО клиента</label>
            <input type="text" class="form-control" id="fio" name="fio" readonly value="{{$info[0]->fio}}">
        </div>
        <div class="form-group">
            <label for="feedback">Текст отзыва клиента</label>
            <textarea class="form-control" id="feedback" rows="4" readonly>{{$info[0]->text}}</textarea>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="service_id" name="service_id" readonly value="{{$info[0]->service_id}}" hidden>
        </div>
        <div class="form-group">
            <label for="text">Текст ответа</label>
            <textarea class="form-control" id="text" name="text" rows="4"></textarea>
        </div>
        <input type="hidden" name="feedback_id" value="{{$info[0]->id}}">
        <input type="hidden" name="client_id" value="{{$info[0]->client_id}}">
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>

@endsection