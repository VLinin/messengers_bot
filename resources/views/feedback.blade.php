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
        <tr>
            <td scope="row">Сервис1</td>
            <td>Иванов Иван</td>
            <td>Хороший продукт!</td>
            <td>Веревка хлопковая</td>
            <td>
                <form method="get" action="/feedback_answ">
                    <button type="submit" class="btn btn-info" >Ответить</button>
                </form>
                <form method="post" action="/feedback_check">
                    <button type="submit" class="btn btn-danger"> Скрыть</button>
                </form>
            </td>
        </tr>
        <tr>
            <td scope="row">Сервис1</td>
            <td>Иванов Иван</td>
            <td>Хороший продукт!</td>
            <td>Веревка хлопковая</td>
            <td>
                <form method="get" action="/feedback_answ">
                    <button type="submit" class="btn btn-info" >Ответить</button>
                </form>
                <form method="post" action="/checkFeedback">
                    <button type="submit" class="btn btn-danger">Скрыть</button>
                </form>
            </td>
        </tr>
        <tr>

        @foreach((new \App\Product_feedback())->getInfoToShow() as $item)
            <tr>
                <td>{{$item->services->name}}</td>
                <td>{{$item->clients->fio}}</td>
                <td>{{$item->text}}</td>
                <td>{{$item->products->name}}</td>
                <td>
                    <form method="get" action="/feedback_answ">
                        <button type="submit" class="btn btn-info" id="btn" value="{{$item->id}}">Ответить</button>
                    </form>
                    <form method="post" action="/checkFeedback">
                        <button type="submit" class="btn btn-danger" id="btn" value="{{$item->id}}">Скрыть</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection