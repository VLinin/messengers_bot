@extends('layouts.app')
@section('content')

    <h1>Формирование рассылок</h1>
    <form action="/addDistribution" method="post">
        <div class="form-group">
            <label for="exampleFormControlTextarea1"><h5>Текст сообщения</h5></label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="8"></textarea>
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="customFile" accept="image/*">
            <label class="custom-file-label" for="customFile">Выберите изображение</label>
        </div>

        <h5>Дата проведения:</h5>
        <input type="date" class="input-group-text" id="date">

        <h5>Сервисы для рассылки:</h5>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gridCheck">
                <label class="form-check-label" for="gridCheck">
                    Сервис1
                </label>
                <br>
                <input class="form-check-input" type="checkbox" id="gridCheck">
                <label class="form-check-label" for="gridCheck">
                    Сервис2
                </label>
            </div>
        </div>


            <div class="form-group">
                <div class="form-check">
                    @foreach(\App\Service::all() as $item)
                        <input class="form-check-input" type="checkbox" id="gridCheck{{$item->id}}">
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