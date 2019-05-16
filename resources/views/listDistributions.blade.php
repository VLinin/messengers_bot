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
        @foreach(\App\Distribution::all()->where('run_date','>',\Carbon\Carbon::now()) as $item)
            <tr>
                <th scope="row">{{$item->id}}</th>
                <td>{{$item->text}}</td>
                <td>{{$item->run_date}}</td>
                <td>
                    <form method="post" action="/cancelDistribution">
                        <input type="hidden" name="btn" value="{{$item->id}}">
                        <button type="submit" class="btn btn-danger" id="btn" value="{{$item->id}}">Отменить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection