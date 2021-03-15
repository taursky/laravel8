@extends('layout.main')
@section('title', 'Личный кабинет заказы')
@section('description', '')
@section('keywords', '')
@section('content')
    <h1>Список заказов <span style="color: #138496">{{Auth::user()->name}}</span></h1>
    <div class="clear_10"></div>
    @if($email_count >0)
        @include('personal.form.mail_order_form', ['count' => $email_count])
        <div class="clear_10"></div>
    @endif
    @if($site_count >0)
        @include('personal.form.site_order_form', ['count' => $site_count])
        <div class="clear_10"></div>
    @endif
    @if($own_count >0)
        @include('personal.form.own_order_form', ['count' => $own_count])
        <div class="clear_10"></div>
    @endif
@endsection
