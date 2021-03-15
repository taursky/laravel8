@extends('layout.main')
@section('title', 'Личный кабинет')
@section('description', '')
@section('keywords', '')
@section('content')
    <div id="enter_menu" class="btn-group" role="toolbar" ><!-- aria-label="" Basic example -->
        <a href="{{route('personal.balance')}}" class="btn btn-secondary">Пополнить счет</a>
        <a href="{{route('personal.order')}}" class="btn btn-info"> Посмотреть свои заказы.</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('out-form').submit();" class="out-btn btn btn-danger">
            Выйти из профиля!
        </a>
        <form id="out-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

    </div>
    <div class="clear_10"></div>
    <h1>Личный кабинет пользователя {{Auth::user()->name}}</h1>
    <div class="personal-mess">
        @if(Session::has('msg'))
            <div class="alert {{Session::get('file-class')}} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Информация!</strong> {{Session::get('msg')}}
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="personal">
                <p class="change-pass">Личные данные</p>
                <form action = "{{route('update.user.data')}}" method = "POST">
                    {{ csrf_field() }}
                    <table class="table form-list">
                        <tr>
                            <td>Email :</td>
                            <td>
                                <span class="normal-text">{{Auth::user()->email}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Логин :</td>
                            <td>
                                <span class="normal-text"><b>{{Auth::user()->name}}</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Дата регистрации: </td>
                            <td>
                                <span class="normal-text">{{date('d.m.Y',strtotime(Auth::user()->created_at))}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Счет : </td>
                            <td><span class="normal-text"><b>{{number_format(Auth::user()->account, 2, '.', ' ')}} руб. </b></span></td>
                        </tr>
                        <tr>
                            <td> </td>
                        </tr>

                        <tr>
                            <td><span class="red-star">*</span> Имя : </td>
                            <td class="{{ $errors->has('new_name') ? ' has-error' : '' }}">
                                <input type="text" name="new_name" class="form-control form-control-sm" id="new_name" value="{{old('new_name')?old('new_name'):Auth::user()->fname}}">
                                @if ($errors->has('new_name'))
                                    <span class="help-block">
                                        <small><strong>{{ $errors->first('new_name') }}</strong></small>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><span class="red-star">*</span> Фамилия : </td>
                            <td class="{{ $errors->has('new_lname') ? ' has-error' : '' }}">
                                <input type="text" name="new_lname" class="form-control form-control-sm" id="new_lname" value="{{old('new_lname')?old('new_lname'):Auth::user()->lname}}">
                                @if ($errors->has('new_lname'))
                                    <span class="help-block">
                                        <small><strong>{{ $errors->first('new_lname') }}</strong></small>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr class="{{ $errors->has('new_birthday') ? ' has-error' : '' }}">
                            <td><span class="red-star">*</span> День рождения : </td>
                            <td>
                                <input type="text" name="new_birthday" class="form-control form-control-sm"
                                       id="new_birthday" value="{{old('new_birthday')?old('new_birthday'):date('d.m.Y', strtotime(Auth::user()->birthday))}}" placeholder="дд.мм.гггг">
                                @if ($errors->has('new_birthday'))
                                    <span class="help-block">
                                        <small><strong>{{ $errors->first('new_birthday') }}</strong></small>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><span class="red-star">*</span> Телефон : </td>
                            <td class="{{ $errors->has('new_phone') ? ' has-error' : '' }}">
                                <input type="text" name="new_phone" class="form-control form-control-sm"
                                       id="new_phone" value="{{old('new_phone')?old('new_phone'):Auth::user()->phone}}"placeholder="+7(700) 255-42-24">
                                @if ($errors->has('new_phone'))
                                    <span class="help-block text-sm">
                                        <small><strong>{{ $errors->first('new_phone') }}</strong></small>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><span class="red-star">*</span> Адрес доставки : </td>
                            <td class="{{ $errors->has('new_address') ? ' has-error' : '' }}">
                                <textarea name="new_address" class="form-control form-control-sm" id="new_address">{{old('new_address')?old('new_address'):Auth::user()->address}}</textarea>
                                @if ($errors->has('new_address'))
                                    <span class="help-block">
                                        <small><strong>{{ $errors->first('new_address') }}</strong></small>
                                    </span>
                                @endif
                            </td>
                        </tr>

                    </table>
                    <br>
                    <input type="submit" class="btn btn-secondary"  value ="Сохранить" />
                </form>
            </div>

            <div class="clear_10"></div>

            <div class="ch_pass">
                <p class="change-pass">Сменить пароль</p>
                <form action = "{{route('change.pass')}}" method = "post">
                    {{ csrf_field() }}
                    <p class="custom-text"><span class="red-star">*</span>Поля отмеченные красной звездочкой, обязательны к заполнению.</p>
                    <ul class="form-list">
                        <!-- поле для идентификации пользователя -->

                        <input type="hidden" name="id" id="id" value="{{Auth::check()? Auth::user()->id: ''}}">

                        <li>Старый пароль:<span class="red-star">*</span></li>
                        <li class="{{ $errors->has('pass') ? ' has-error' : '' }}">
                            <input type="password" class="form-control form-control-sm" name="pass">
                            @if ($errors->has('pass'))
                                <span class="help-block">
                                        <small><strong>{{ $errors->first('pass') }}</strong></small>
                                    </span>
                            @endif
                        </li>
                        <li>Новый пароль(не менее 5 символов):<span class="red-star">*</span></li>
                        <li class="{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password" class="form-control form-control-sm" name="password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <small><strong>{{ $errors->first('password') }}</strong></small>
                                    </span>
                            @endif
                        </li>
                        <li>Повторите новый пароль:<span class="red-star">*</span></li>
                        <li>
                            <input type="password" class="form-control form-control-sm" name="password_confirmation">
                        </li>
                        <br>
                    </ul>
                    <button class="btn btn-secondary" type="submit" name="chengePass" id="chengePass" value = "save">Сохранить</button>

                    <div class="clear"></div>
                </form>
            </div>

        </div>
        {{-- Для юридических лиц --}}
        @if (empty($is_company) && !Session::has('is_legal'))
            @php
                $action = 'create.legal_entiti';
            @endphp
        <div class="button_active_form">
            <button class="ur-reg btn btn-secondary" type="submit" onclick="regUrClick()" ><b>Регигистрация как Ю.Л.</b></button>
        </div>
        <script type="text/javascript">
            function regUrClick(){
                $(".ur-reg").hide();
                $(".ur_face_reg").show();
                $(".abort-reg").show();
            }
            function abortUrClick() {
                $(".ur-reg").show();
                $(".ur_face_reg").hide();
                $(".abort-reg").hide();
            }
        </script>
        <div class="col-xl-6">

            <div class="ur_face_reg" style="display: none">
                <p class="change-pass">Реквизиты Компании</p>
                <p class="custom-text"><span class="red-star">*</span>Поля отмеченные красной звездочкой, обязательны к заполнению.</p>
                @include('personal.form.legal_entity_form', ['is_company' => $is_company, 'action' => $action])
                <div class="clear_10"></div>
                <button class="abort-reg btn btn-danger" type="submit" onclick="abortUrClick()" style="display: none"><b>Отмена регистрации Ю.Л.</b></button>

            </div>
        </div>
        @else
        <div class="col-xl-6">
            <div class="ur_face">
                <div class="mess">
                    {{--TODO сообщение ERROR --}}
                </div>
                <p class="change-pass">Реквизиты Компании</p>
                    @php
                        empty($is_company)?$action = 'create.legal_entiti':$action = 'update.legal_entiti';
                    @endphp
                    @include('personal.form.legal_entity_form', ['is_company' => $is_company, 'action' => $action])
            </div>
        </div>
        @endif
        {{--/Для юридических лиц --}}
    </div>
<div class="clear clear_20"></div>
    <script>
        $(document).ready(function(){
            //$('#new_phone').mask('+7 (999) 999-99-99');
            //$('input[name="new_phone"]').mask('+7 (___) ___-__-__');
        });
    </script>
    <script src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
@endsection
