@extends('layout.main')
@section('title', 'Заказ по Email отправлен')
@section('description', '')
@section('keywords', '')
@section('content')
    <a href="{{route('cart')}}" class="btn btn-warning">Вернуться в корзину</a>
    <h1>Заказ по Email (отправка)</h1>
    @if($mess)
        {!! $mess !!}
    @endif
    @if($order)
    <table class="table table-hover">
        <thead class="table-info">
        <tr>
            <td style="width: 40px">№</td>
            <td>Наименование</td>
            <td>Артикул</td>
            <td>Цена</td>
            <td>Количество</td>
            <td>Сумма</td>
            <td>Доставка</td>
        </tr>
        </thead>
        @foreach($order->data as $item)
            @php
                $delivery = App\Delivery::where('id', $order->delivery_id)->value('name');
            @endphp
           <tr>
               <td style="width: 40px">{{$loop->iteration}}</td>
               <td>{{$item['title']}}</td>
               <td>{{$item['articul']}}</td>
               <td style="min-width: 90px">{{number_format(App\Model\PartsModel::finalPrice($item['price']), 0, '.', ' ')}}</td>
               <td>{{intval($item['count'])}}</td>
               <td style="min-width: 100px">{{number_format(App\Model\PartsModel::finalPrice($item['price']) * intval($item['count']), 0, '.', ' ')}}</td>
               <td>{{$delivery}}</td>
            </tr>
        @endforeach
    </table>
    <h3>Сумма: {{number_format($sum, 0, '.', ' ')}} <i class="fa fa-rub"></i> </h3>
    @else
        <h3 class="text-danger">Нельзя отправить пустой заказ</h3>
    @endif
@endsection
