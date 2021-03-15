<h1>Запрос на поиск запчастей </h1>
@if(Session::has('mess'))
    {!! Session::get('mess') !!}
@endif
<div class="clear_10"></div>
<form class="zakaz_form" action="{{route('send.spare_parts.mail')}}" method="post">
    {{ csrf_field() }}
    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name"><h4><span class="red-star">* </span>Имя    :</h4></label>
        <input type="text" class="form-control" name="name" placeholder="Введите ваше имя" value="{{old('name')?old('name'):(Auth::guest()?'': Auth::user()->name) }}" required>
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email"><h4><span class="red-star">* </span>Email:</h4></label>
        <input type="email" class="form-control"  placeholder="Введите электронный адрес"  name="email" value="{{old('email')?old('email'): (Auth::guest()?'': Auth::user()->email)}}" required>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('tel') ? ' has-error' : '' }}">
        <label for="tel"><h4><span class="red-star">* </span>Телефон:</h4></label>
        <input type="text" class="form-control" name="tel" placeholder="Введите номер телефона"   value="{{old('tel')?old('tel'): (Auth::user()?Auth::user()->phone:'')}}" required>
        @if ($errors->has('tel'))
            <span class="help-block">
                <strong>{{ $errors->first('tel') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('producer') ? ' has-error' : '' }}">
        <label for="producer"><h4><span class="red-star">* </span>ИЗГОТОВИТЕЛЬ:</h4></label>
        <input type="text" class="form-control" name="producer" placeholder="Введите наименование изготовителя, например &laquo;SUZUKI&raquo;" value="{{old('producer')?old('producer'):''}}" required>
        @if ($errors->has('producer'))
            <span class="help-block">
                <strong>{{ $errors->first('producer') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('model') ? ' has-error' : '' }}">
        <label for="model"><h4><span class="red-star">* </span>Марка (модель):</h4></label>
        <input type="text" class="form-control" name="model" placeholder="Введите марку, например &laquo;Escudo&raquo; " value="{{old('model')?old('model'):''}}" required>
        @if ($errors->has('model'))
            <span class="help-block">
                <strong>{{ $errors->first('model') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('serialnumber') ? ' has-error' : '' }}">
        <label for="serialnumber"><h4><span class="red-star">* </span>Номер кузова (VIN код):</h4></label>
        <input type="text" class="form-control" name="serialnumber" placeholder="Введите номер кузова или VIN, например &laquo;А555345 258&raquo; "   value="{{old('serialnumber')?old('serialnumber'):''}}" required>
        @if ($errors->has('serialnumber'))
            <span class="help-block">
                <strong>{{ $errors->first('serialnumber') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('engine') ? ' has-error' : '' }}">
        <label for="engine"><h4><span class="red-star">* </span>Марка ДВС:</h4></label>
        <input type="text" class="form-control" name="engine" placeholder="Введите Марку ДВС, например &laquo;1HDT 00000001&raquo; "   value="{{old('engine')?old('engine'):''}}" required>
        @if ($errors->has('engine'))
            <span class="help-block">
                <strong>{{ $errors->first('engine') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('catnum') ? ' has-error' : '' }}">
        <label for="catnum"><h4>Артикул детали:</h4></label>
        <input type="text" class="form-control" name="catnum" placeholder="Введите Артикул, например &laquo;C450-7B50-10&raquo; " value="{{old('catnum')?old('catnum'):''}}" required>
        @if ($errors->has('catnum'))
            <span class="help-block">
                <strong>{{ $errors->first('catnum') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('message') ? ' has-error' : '' }}">
        <label for="message"><h4><span class="red-star">* </span><strong>Описание заявки:</strong></h4></label>
        <textarea class="form-control" rows="5" name="message" required>{{old('message')?old('message'):''}}</textarea>
        @if ($errors->has('message'))
            <span class="help-block">
                <strong>{{ $errors->first('message') }}</strong>
            </span>
        @endif
    </div>

    <p>
        <button class="btn btn-info" type="submit">Отправить сообщение</button>
    </p>
</form>

<script>
    $(document).ready(function(){
        //$('#tel').mask('+7 (999) 999-99-99');
        //$('input[name="new_phone"]').mask('+7 (___) ___-__-__');
    });
</script>
{{--<script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>--}}

