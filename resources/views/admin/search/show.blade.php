@extends('layout.admin')
@section('content')
<h1>Количество запросов</h1>
    @php
        //var_dump($searches);
    @endphp
    <table class="table table-hover">
        <thead class="table-info">
        <tr>
            <td></td>
            <td>Артикул</td>
            <td>Количество запросов</td>
        </tr>

        </thead>



    @foreach($searches as $search)
        <tr>
            <td>{{$loop->iteration + $page}}</td>
            <td>{{$search->articul}}</td>
            <td>{{$search->count}}</td>
        </tr>

    @endforeach
    </table>
<div class="own-pagination">
    {{$searches->render()}}
</div>
@endsection
