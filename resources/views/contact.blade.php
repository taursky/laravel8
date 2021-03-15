@extends('layout.main')
@section('title', 'контакты')
@section('description', 'автозапчасти для японских автомобилей.')
@section('keywords', '')

@section('content')
    <h1>Контактные данные</h1>
    <div class="contact_adr">
        @if($text_pages)
            {!! $text_pages->text !!}
        @endif
    </div>

    @if(Session::has('mess'))
        {!! Session::get('mess') !!}
    @endif
    <p>
        <strong>Форма для отправки сообщения:</strong>
    </p>
    <form class="contact_form" action="{{route('send.contact.mail')}}" method="post">
        {{ csrf_field() }}
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name"><span class="red-star">* </span> Ваше Имя:</label>
            <input type="text" class="form-control" placeholder="Введите ваше Имя" name="name"
                   value="{{old('name')?old('name'):(Auth::guest()?'': Auth::user()->name) }}" required>
            @if ($errors->has('name'))
                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email"><span class="red-star">* </span> Ваш Email:</label>{{--  --}}
            <input type="text" class="form-control" name="email"
                   placeholder="Введите ваш email, например &laquo;name@mail.com&raquo;"
                   value="{{old('email')?old('email'): (Auth::guest()?'': Auth::user()->email)}}">
            @if ($errors->has('email'))
                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('tel') ? ' has-error' : '' }}">
            <label for="tel"><span class="red-star">* </span> Телефон:</label>
            <input type="text" class="form-control" name="tel"
                   placeholder="Введите номер телефона, например +7 (999) 555-55-55"
                   value="{{old('tel')?old('tel'): ''}}">
            @if ($errors->has('tel'))
                <span class="help-block">
                                        <strong>{{ $errors->first('tel') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('message') ? ' has-error' : '' }}">
            <label for="message"><span class="red-star">* </span> Ваше сообщение:</label>
            <textarea class="form-control" rows="5" name="message">{{old('message')?old('message'): ''}}</textarea>
            @if ($errors->has('message'))
                <span class="help-block">
                    <strong>{{ $errors->first('message') }}</strong>
                </span>
            @endif
        </div>
                <div class="form-group">
                    {!! NoCaptcha::display() !!}
                    <br>
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>

        <button type="submit" class="btn btn-outline-info">Отправить сообщение</button>

    </form>
    <div class="clear_20"></div>
@endsection
