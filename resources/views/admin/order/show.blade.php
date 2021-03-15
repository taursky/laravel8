@extends('layout.admin')
@section('title', ' | Просмотр заказа')
@section('content')

<h1>Просмотр заказа</h1>
    <a href="{{$link}}" class="btn btn-success">Вернуться на страницу просмотра заказов</a>

<h1>Заказ № {{$order->nom}}</h1>
<p><b>Дата заказа</b>: {{date('d.m.Y H:i',$order->datus)}}</p>
<p><b>Имя отправителя</b> (из формы): {{$order->name}} (из базы): {{$user->name}}</p>
<p><b>Email отправителя</b> (из формы): {{$order->email}} (из базы): {{$user->email}}</p>
<p><b>Описание:</b> {{$order->description}}</p>
<h5>Заказ</h5>
<table class="table table-hover">
    <thead class="table-info">
    <tr>
        <td style="width: 40px">№</td>
        <td>Наименование</td>
        <td>Марка</td>
        <td>Артикул</td>
        <td>Count</td>
        <td>Цена</td>
        <td>Кол-во</td>
        <td>Сумма</td>
        <td>Вес</td>
        <td>Доставка</td>
        <td>Поставщик</td>
    </tr>
    </thead>
    @foreach($order->data as $item)
        @if($item['provider'] == 'из японии')
            @php
                $weight = \App\Detal::where('id', $item['detal_id'])->value('weight');
            @endphp
        @else
            @php
                $weight = 'не имеет значения';
            @endphp
        @endif
        @php
            isset($item['brand'])?$brand = $item['brand']:$brand = '';
            $delivery = \App\Delivery::where('id', $order->delivery_id)->value('name');
        @endphp
        <tr>
            <td style="width: 40px">{{$loop->iteration}}</td>
            <td>{{$item['title']}}</td>
            <td>{{$brand}}</td>
            <td style="width:150px;">{{\App\Model\PartsModel::createNiceArticul($item['articul'], $brand)}}</td>
            <td>{{intval($item['count'])}}</td>
            <td style="min-width: 120px">{{number_format(App\Model\PartsModel::finalPrice($item['price']), 0, '.', ' ')}}</td>
            <td>{{intval($item['count'])}}</td>
            <td style="min-width: 120px">{{number_format(App\Model\PartsModel::finalPrice($item['price']) * intval($item['count']), 0, '.', ' ')}}</td>
            <td>{{$weight}}</td>
            <td class="text-warning">{{$delivery}}</td>
            <td class="text-info">{{$item['provider']}}</td>
        </tr>
    @endforeach
</table>
<h5>Сумма: {{number_format(App\Model\PartsModel::finalPrice($order->summa), 0, '.', ' ')}} <i class="fa fa-rub"></i> </h5>
@endsection
