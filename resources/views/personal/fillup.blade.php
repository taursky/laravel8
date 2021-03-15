@extends('layout.main')
@section('title', 'Пополнение счета')
@section('description', '')
@section('keywords', '')
@section('content')
    <div class="popolbal">
        <form action="{{route('personal.balance')}}" method="get">
            {{ csrf_field() }}
            <input type="submit" class="btn btn-secondary" name="null" value="Посмотреть платежи">
        </form>
    </div>
    <div class="to-popop">
        <p>Пополнение баланса произойдет в течении нескольких минут после перевода на WalletOne<br>
            При возникновении вопросов воспользуйтесь страничкой <b>"Контакты"</b>.<br>
            Или сообщите на email : <b>{{ config('app.email') }}</b></p>
    </div>
    <div id="form">
        @include('personal.form.fill_up_form')
    </div>
@endsection
