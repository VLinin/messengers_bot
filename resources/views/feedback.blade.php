@extends('layouts.app')
@section('content')
    <h1>Сообщения пользователей</h1>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Платформа</th>
            <th scope="col">Имя</th>
            <th scope="col">Текст</th>
            <th scope="col">Товар</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach((new \App\Product_feedback())->getInfoToShow() as $item)
            <tr>
                <td>{{$item->service}}</td>
                <td>{{$item->fio}}</td>
                <td>{{$item->text}}</td>
                <td>{{$item->product}}</td>
                <td>
                    <form method="get" action="/feedback_answ">
                        <input type="hidden" value="{{$item->id}}" name="id">
                        <button type="submit" class="btn btn-info" id="btn">Ответить</button>
                    </form>
                    <form method="post" action="/checkFeedback">
                        <input type="hidden" value="{{$item->id}}" name="id">
                        <button type="submit" class="btn btn-danger" id="btn">Скрыть</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection