@extends('layout.main')
@section('title', 'Тех.поддержка - создать сообщение')
@section('description', '')
@section('keywords', '')
@section('content')
<div class="page">
    <div class="clear_10"></div>
    <div class="btn-group">
        <form action="/ticket" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="ticket_list" value="net">
            <input type="submit" class="btn btn-secondary" value="Мои запросы">
        </form>
                    <!--<a href="tick" class="btn btn-primary"></a>-->
                    <!--  <a href="/test?t=new">Создать</a>-->
        <form action="/make_message" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="t" value="new"/>
            <input type="submit" class="btn btn-primary" value="Создать">
        </form>
    </div>
                <div class="clear_20"></div>
        <div class="content_line"></div>

                @php
                    if (isset($err) && $err)echo $err;
                @endphp


        <form action="{{route('create_supp')}}" method="post">
            {{ csrf_field() }}
            <table class="table ">
                <tr class="form-group ">
                    <td class="font-weight-bold text-lg-right {{ $errors->has('cat') ? ' has-error' : '' }}">Категория </td>
                    <td>
                        <select name="cat" class="form-control">
                            @php
                                $ticket_cat = \App\TicketCategory::where('status', 1)->orderBy('sort', 'asc')->get();
                            @endphp
                            @foreach($ticket_cat as $cat)
                                <option value="{{$cat->id}}">{{$cat->title}}</option>
                            @endforeach
                            </select>
                        @if ($errors->has('cat'))
                            <span class="help-block">
                                <strong>{{ $errors->first('cat') }}</strong>
                            </span>
                        @endif
                    </td>

                </tr>
                <tr class="form-group">
                    <td class="font-weight-bold text-lg-right {{ $errors->has('subject') ? ' has-error' : '' }}">Тема</td>
                    <td>
                        <input name="subject" class="form-control" type="text" size="30" maxlength="255" value="{{ old('subject') }}">
                        @if ($errors->has('subject'))
                            <span class="help-block">
                                <strong>{{ $errors->first('subject') }}</strong>
                            </span>
                        @endif
                    </td>
                </tr>

                <tr class="form-group">
                    <td class="font-weight-bold text-lg-right {{ $errors->has('text') ? ' has-error' : '' }}">Сообщение</td>
                    <td>
                        <textarea name="text" class="form-control" >{{ old('text') }}</textarea>
                        @if ($errors->has('text'))
                            <span class="help-block">
                        <strong>{{ $errors->first('text') }}</strong>
                    </span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input name="newtick" class="btn btn-outline-secondary" type="submit" value="Отправить" >
                    </td>
                </tr>
            </table>
        </form>

</div>
@endsection
