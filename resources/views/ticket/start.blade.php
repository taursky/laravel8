@extends('layout.main')
@section('title', 'Тех.поддержка')
@section('description', '')
@section('keywords', '')
@section('content')
<div class="page">
    <div class="clear_10"></div>
    <!--Menu -->
    <div class="btn-group">
                    <form action="{{ route('ticket') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="ticket_list" value="net">
                        <input type="submit" class="btn btn-secondary" value="Мои запросы">
                    </form>
                    <!--<a href="tick" class="btn btn-primary"></a>-->
                    <!--  <a href="/test?t=new">Создать</a>-->
                    <form action="{{route('make.message')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="t" value="new"/>
                        <input type="submit" class="btn btn-primary" value="Создать">
                    </form>
    </div>
        <!--End menu -->
    <div class="clear_20"></div>
    <div class="content_line"></div>
                @php
                    if (isset($err) && $err)echo $err;
                    $i = 0;
                @endphp
                @if($tickets)
                    <table class="table table-hover table-bordered">
                        <thead class="table-primary">
                        <tr class="title">
                            <td>Категория</td>
                            <td>Тема</td>
                            <td>Дата создания</td>
                            <td></td>
                        </tr>
                        </thead>
                        @foreach ($tickets as $ticket)
                            @php
                                $id_mes = $ticket->id;
                                $count = \App\TicketMes::where(['stat' => 0, 'id_ticket' => $ticket->id])->count();
                                $ticket_cat = \App\TicketCategory::where('id', $ticket->category)->first();
                                if($count == 0)$col = 'black';
                                else $col = 'red';
                                $val_look = 'Перейти к запросу ( Ответов: '.number_format($ticket->answers, 0).') | (новых : <span style="color:'.$col.'">'.$count.'</span>)';
                                $style_look = 'style = "border:0;margin:0;padding:0;background:#FFFFFF;cursor:pointer;color:#2F92FC;"';
                                $i++;
                            @endphp
                            <tr>
                                <td>{{$ticket_cat->title}}</td>
                                <td>{{htmlspecialchars($ticket->subject)}}</td>
                                <td>{{date('d.m.Y',strtotime($ticket->dat))}}</td>
                                <td>
                                    <form action="{{route('support.message')}}" id="look{{$i}}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$ticket->id}}">
                                        <button type="submit" form="look{{$i}}" {!! $style_look !!}>{!! $val_look !!}</button>
                                    </form>

                                <!--<a href='?h={{$ticket->id}}'>
                                        Перейти к запросу ( Ответов: {{number_format($ticket->answers, 0)}}) | (новых : <span style="color:{{$col}};">{{$count}}</span>)
                                    </a>-->
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                        </button>
                        <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> Вас нет тикетов.
                    </div>
                @endif
</div>
@endsection
