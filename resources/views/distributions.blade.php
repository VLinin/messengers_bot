@extends('layouts.app')
@section('content')

    <h1>Формирование рассылок</h1>
    <form action="/addDistribution" method="post"  enctype="multipart/form-data">
        <div class="form-group">
            <label for="Textarea"><h5>Текст сообщения</h5></label>
            <textarea class="form-control" id="Textarea" name="text" rows="8"></textarea>
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="customFile" accept="image/*" name="image">
            <label class="custom-file-label" for="customFile">Выберите изображение</label>
        </div>

        <h5>Дата проведения:</h5>
        <input type="date" class="input-group-text" id="date" name="date">

        <h5>Сервисы для рассылки:</h5>
            <div class="form-group">
                <div class="form-check">
                    @foreach(\App\Service::all()->where('enable','=',1) as $item)
                        <input class="form-check-input" type="checkbox" id="gridCheck{{$item->id}}" name="gridCheck{{$item->id}}">
                        <label class="form-check-label" for="gridCheck">
                            {{$item->name}}
                        </label>
                        <br>
                    @endforeach
                </div>
            </div>

        <button type="submit" class="btn btn-primary" align="center">Отправить</button>
    </form>

@endsection