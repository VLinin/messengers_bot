@extends('layouts.app')
@section('content')
    <h1>Изменение ключей доступа платформ</h1>
    <form method="post" action="/chngToken">
        <div class="form-group">
            <label for="text">Сервис 1</label>
            <input type="text" class="form-control" id="text" aria-describedby="Help" placeholder="Введите ключ">
            <small id="Help" class="form-text text-muted">Недействительный ключ нарушит работу с сервисом!</small>
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
    @foreach(\App\Service::all() as $item)
        <form  method="post" action="/chngToken">
            <div class="form-group">
                <label for="text">{{$item->name}}</label>
                <input type="text" class="form-control" id="text" aria-describedby="Help" placeholder="Введите ключ">
                <small id="Help" class="form-text text-muted">Недействительный ключ нарушит работу с сервисом!</small>
            </div>
            <button type="submit" class="btn btn-primary" value="{{$item->id}}" id="btn">Отправить</button>
        </form>
    @endforeach
@endsection