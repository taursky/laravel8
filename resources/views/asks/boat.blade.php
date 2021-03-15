@extends('layout.main')
@section('title', 'Заказ запчастей для катеров')
@section('description', '')
@section('keywords', '')
@section('content')

    <h1>Запчасти для водно-моторной техники</h1>
    <div id="main_text">
        <div class="text_pages">
            {{-- вставляем текст из базы данных --}}
            @if($text_avto)
                {!! $text_avto->text !!}
            @endif
        </div>
    </div>
    @include('form.spare_parts')

@endsection
