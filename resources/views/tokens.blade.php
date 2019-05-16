@extends('layouts.app')
@section('content')
    <h1>Изменение ключей доступа платформ</h1>
    @foreach(\App\Service::all()->where('enable','=',1) as $item)
        <form  method="post" action="/chngToken">
            <div class="form-group">
                <label for="text">{{$item->name}}</label>
                <input type="text" class="form-control" id="text" aria-describedby="Help" placeholder="Введите ключ" name="text">
                <small id="Help" class="form-text text-muted">Недействительный ключ нарушит работу с сервисом!</small>
                <input name="id" value="{{$item->id}}" type="hidden">
            </div>
            <button type="submit" class="btn btn-primary" value="{{$item->id}}" id="btn">Отправить</button>
        </form>
    @endforeach
@endsection