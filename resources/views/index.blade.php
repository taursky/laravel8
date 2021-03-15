@extends('layout.main')
@section('title', 'автомагазин')
@section('description', 'автозапчасти для японских автомобилей.')
@section('keywords', '')

@section('content')
    <section class="">{{-- mainpage_slider --}}
        <div class="main-slider-box">
            <div id="sldr" class="main_slider">
                <slider-component></slider-component>
            </div>
        </div>
    </section>
    <div class="clear_40"></div>
    <section class="">
        <div class="tex_1">
            {{-- вставляем текст из базы данных --}}
            @if($text)
                {!! $text->text !!}
            @endif
        </div>
    </section>

    <script>
        window.images = '@json($images)';
    </script>
@endsection
