@extends('layout.main')
@section('title', 'Тех.поддержка - просмотр сообщения')
@section('description', '')
@section('keywords', '')
@section('content')
<div class="page">
   <div class="clear_10"></div>

                <div class="btn-group">
                    <form action="{{route('ticket')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="ticket_list" value="net">
                        <input type="submit" class="btn btn-secondary" value="Мои запросы">
                    </form>
                    <form action="{{route('make.message')}}" method="post">
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
                <strong>Категория:</strong> - {{$ticket_category}}
                <br>
                <strong> Тема:</strong> {{$ticket->subject}}
                <br>
                <strong>Дата создания:</strong> {{date('d.m.Y H:i', strtotime($ticket->dat))}}
                <div class="content_line"></div>
                <div class="clear_20"></div>
                @foreach($ticket_mess as $mess)
                    <br>
                    <strong>{{$mess->sender}}</strong> [ {{date('d.m.Y H:i', strtotime($mess->dat))}} ]:<br>
                <div class='sup_mess'>
                    <p>{{htmlspecialchars_decode($mess->text)}}</p>
                </div><br>
                @php
                    $res_adm = \App\TicketMes::where('id_mess_answ', $mess->id)->count();
                @endphp
                    @if($res_adm > 0)
                        @foreach(\App\TicketMes::where(['id_mess_answ' => $mess->id])->get() as $row3)
                            <br><strong style='color:red;'> Служба поддержки </strong> [ {{date('d.m.Y H:i', strtotime($row3->dat))}} ]:<br>
                        <div class='adm_mess ml-5'>
                            <p>{{htmlspecialchars_decode($row3->text)}}</p>
                        </div>
                        <br>
                        @endforeach
                    @endif

                @endforeach

                <div class="send_ticket">
                    <form action="{{route('create_tic')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="subject" value="{{htmlspecialchars($ticket->subject)}}"/>
                        <input type="hidden" name="cat" value="{{$ticket->category}}"/>
                        <input type="hidden" name="id" value="{{$ticket->id}}"/>
                        <input type="hidden" name="login" value="{{$ticket->login}}"/>
                        <input type="hidden" name="email" value="{{$ticket->email}}"/>
                        <input name="recipient" type="hidden" size="30" maxlength="30" value="{{$ticket->id}}" /><br />
                        <p class="{{ $errors->has('text') ? ' has-error' : '' }}"> Текст: </p>
                        <textarea name="text" class="form-control" cols="30" rows="5">{{ old('text') }}</textarea><br />
                        @if ($errors->has('text'))
                            <span class="help-block">
                        <strong>{{ $errors->first('text') }}</strong>
                    </span>
                        @endif
                        <input name="newok" class="btn btn-outline-info" type="submit" value="Отправить" />
                    </form>
                </div>
                <div class="close_ticket">
                    <form action="{{route('ticket_close')}}" id="close" method="post">
                        {{ csrf_field() }}
                        <br>
                        <input type="hidden" name="id" value="{{$ticket->id}}">
                        <p>
                            <!--<input type="submit" class="btn btn-outline-danger" name="close_ticket" value="закрыть тикет"/>-->
                            <button type="submit" form="close" class="btn btn-outline-danger">закрыть тикет</button>
                            закрыть запрос (Если вопрос решен).</p>
                    </form>
                </div>
</div>
@endsection
