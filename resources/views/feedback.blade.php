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
            <td><button type="button" class="btn btn-info">Ответить</button></td>
        </tr>
        <tr>

        @foreach(\App\Distribution::query() as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->text}}</td>
                <td>{{$item->run_date}}</td>
                <td>{{$item->run_date}}</td>
                <td><button type="button" class="btn btn-info">Ответить</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection