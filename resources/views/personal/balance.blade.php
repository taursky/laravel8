@extends('layout.main')
@section('title', 'Балланс счета')
@section('description', '')
@section('keywords', '')
@section('content')
    <div class="popolbal">
        <form action="{{route('personal.balance.fillup')}}" method="post">
            {{ csrf_field() }}
            <input type="submit" class="btn btn-secondary" name="upball" value="Пополнить счет">
        </form>
    </div>
    <h1>История пополнения счета.</h1>
    <table class="table table-hover">
        <thead class="table-info">
        <tr>
            <th>Дата</th>
            <th>Сумма пополнения</th>
            <th>Статус </th>
        </tr>
        </thead>
        @foreach($history as $merch)
            @php
                $status = '<span style="color:grey">неопределён</span>';
                if ($merch->stat == 1) $status = '<span style="color:green">пополнение</span>';
                if ($merch->stat == 2) $status = '<span style="color:red">оплата</span>';
            @endphp
            <tr>
                <td>{{date("d.m.Y H:i",$merch->time)}}</td>
                <td>{{number_format($merch->sum, 2, ".", " ")}}</td>
                <td>{!! $status !!}</td>
            </tr>
        @endforeach

    </table>

@endsection
