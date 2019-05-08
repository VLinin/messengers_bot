@extends('layouts.app')
@section('content')
    <h1>Список рассылок</h1>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Текст</th>
            <th scope="col">Дата</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Пробный текст рыссылки для наблюдения офорлмения</td>
            <td>22.05.2019</td>
            <td><button type="button" class="btn btn-danger">Отменить</button></td>
        </tr>
        <tr>
            <th scope="row">1</th>
            <td>Пробный текст рыссылки для наблюдения офорлмения</td>
            <td>22.05.2019</td>
            <td><button type="button" class="btn btn-danger">Отменить</button></td>
        </tr>
        <tr>
            <th scope="row">1</th>
            <td>Пробный текст рыссылки для наблюдения офорлмения</td>
            <td>22.05.2019</td>
            <td><button type="button" class="btn btn-danger">Отменить</button></td>
        </tr>
        @foreach(\App\Distribution::query() as $item)
            <tr>
                <th scope="row">{{$item->id}}</th>
                <td>{{$item->text}}</td>
                <td>{{$item->run_date}}</td>
                <td><button type="button" class="btn btn-danger">Отменить</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection