@extends('layout.main')
@section('title', 'Заказ с личного счета')
@section('description', '')
@section('keywords', '')
@section('content')
    <a href="{{route('cart')}}" class="btn btn-warning">Вернуться в корзину</a>
    <h1> Заказ с личного счета</h1>
    {{-- TODO $error paste !!!!! --}}
    @if($error)
        {!! $error !!}
        <!--<a href="{{route('personal.balance')}}" class="btn btn-info">Пополнить баланс счета</a>-->
        <form action="{{route('personal.balance.fillup')}}" method="post">
            {{ csrf_field() }}
            <input type="submit" class="btn btn-info" name="upball" value="Пополнить баланс счета">
        </form>
    @elseif((!$error && $sale_cart && count($sale_cart) > 0) || ($oem_cart && count($oem_cart) > 0) || ($catalog_cart && count($catalog_cart) > 0))
        @include('order.forms.send')
    @else
        <h1>У Вас нет товаров в корзине для заказа</h1>
    @endif

@endsection
