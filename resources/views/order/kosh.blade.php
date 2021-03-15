@extends('layout.main')
@section('title', 'Оплата на "Единый кошелёк"')
@section('description', '')
@section('keywords', '')
@section('content')
    <a href="{{route('cart')}}" class="btn btn-warning">Вернуться в корзину</a>
    <h1>Оплата на "Единый кошелёк"</h1>
    @php
        //var_dump($request->check_self);
    @endphp

@endsection
