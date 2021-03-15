@extends('layout.main')
@section('title', 'Заказы на сайте')
@section('description', '')
@section('keywords', '')
@section('content')
    <h1>{{$title}}  {{Auth::user()->name}}</h1>
    @if($type != 1 && $count_1 > 0)
    <div style="float: left">
        @include('personal.form.mail_order_form')
    </div>
    @endif
    @if($type != 3 && $count_3 > 0)
    <div style="float: left">
        @include('personal.form.own_order_form')
    </div>
    @endif
    @if($type != 2 && $count_2 > 0)
    <div style="float: left">
        @include('personal.form.site_order_form')
    </div>
    @endif
    @include('personal.form.return_form')
    @if($order && count($order) >0)
        <div class="clear clear_10"></div>
        <table class="table table-hover text-center">
            <thead class="table-info">
            <tr>
                <th class=" p_5">№</th>
                <th class=" p_5">Дата</th>
                <th class=" p_5">Имя</th>
                <th class=" p_5">Email</th>
                <th class=" p_5">Сообщение</th>
                <th class=" p_5">Подробнее</th>
            </tr>
            </thead>
            @foreach($order as $item)
                @php
                    //$message = explode(';', );//mb_substr($item->mess,0,mb_strrpos(mb_substr($item->mess,0, 140,'utf-8'),' ',0),'utf-8');
                    // Вывод и структура заявки переделать
                    //$message = $message[5];
                //UPDATE `orders` SET `id`=[value-1],
                //`nom`=[value-2],
                //`user_id`=[value-3],
                //`name`=[value-4],
                //`email`=[value-5],
                //`delivery_id`=[value-6],
                //`description`=[value-7],
                //`data`=[value-8],
                //`datus`=[value-9],
                //`type`=[value-10],
                //`pay`=[value-11],
                //`status`=[value-12] WHERE 1
                @endphp
                <tr>
                    <td style="text-align: center;">{{$loop->iteration}}</td>
                    <td align="center">{{date('d.m.Y',$item->datus)}}</td>
                    <td align="center">{{$item->name}}</td>
                    <td align="center">{{$item->email}}</td>
                    <td align="center">{!! $item->description !!}</td>
                    <td align="center">
                        <form action="{{route('mail.item')}}" class="no-buttom" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$item->id}}">
                            <input type="hidden" name="type" value="{{$item->type}}">
                            <button type="submit">
                                <i class="fa fa-search fa-2x"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="own-pagination">
            {{$order->render()}}
        </div>
    @else
        <h3>{{$title}} отсутствуют.</h3>
    @endif
@endsection
