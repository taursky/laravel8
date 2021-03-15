@extends('layout.main')
@section('title', 'Просмотр заказа')
@section('description', '')
@section('keywords', '')
@section('content')

    <div style="float: left">
        @if($type == 1)
            @include('personal.form.mail_order_form')
        @endif
        @if($type == 2)
            @include('personal.form.site_order_form')
        @endif
        @if($type == 3)
            @include('personal.form.own_order_form')
        @endif
    </div>
    @include('personal.form.return_form')
    <div class="clear_10"></div>
    @if($order)
        <h1>Заказ № {{$order->nom}}</h1>
        <p><b>Дата заказа</b>: {{date('d.m.Y',$order->datus)}}</p>
        <p><b>Имя отправителя</b>: {{$order->name}}</p>
        <p><b>Email отправителя</b>: {{$order->email}}</p>
        <p><b>Комменарий</b>: {{$order->description}}</p>
        <p><b>Способ доставки</b>: {{$delivery->name}}</p>
        <h5>Заказ</h5>
        <table class="table table-hover">
            <thead class="table-info">
            <tr>
                <td style="width: 40px">№</td>
                <td>Наименование</td>
                <td>Марка</td>
                <td>Артикул</td>
                <td>Цена</td>
                <td>Кол-во</td>
                <td>Сумма</td>
                <td>Доставка</td>
            </tr>
            </thead>
            @php
                    $delivery = \App\Delivery::where('id', $order->delivery_id)->value('name');
            @endphp
            @foreach($order->data as $item)
                @php
                    isset($item['brand'])?$brand = $item['brand']:$brand = null;
                @endphp
                <tr>
                    <td style="width: 40px">{{$loop->iteration}}</td>
                    <td>{{$item['title']}}</td>
                    <td>{{$brand}}</td>
                    <td>{{\App\Model\PartsModel::createNiceArticul($item['articul'], $brand)}}</td>
                    <td style="min-width: 120px">{{number_format(App\Model\PartsModel::finalPrice($item['price']), 0, '.', ' ')}}</td>
                    <td>{{intval($item['count'])}}</td>
                    <td style="min-width: 120px">{{number_format(App\Model\PartsModel::finalPrice($item['price']) * intval($item['count']), 0, '.', ' ')}}</td>
                    <td>{{$delivery}}</td>
                </tr>
            @endforeach
        </table>
        <h5>Сумма: {{number_format(App\Model\PartsModel::finalPrice($order->summa), 0, '.', ' ')}} <i class="fa fa-rub"></i> </h5>
        <div class="clear_10"></div>
        <form action="{{route('delete.order')}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$order->id}}">
            <input type="submit" class="btn btn-danger" title="Удалить заявку из базы" value="Удалить заявку">
        </form>
        <div class="clear_10"></div>
    @else
        <h3>У Вас нет заказов на Email</h3>
    @endif
@endsection
