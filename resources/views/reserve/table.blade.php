@extends('layout.main')
@section('title', 'Загрузить прайс')
@section('description', '')
@section('keywords', '')
@section('content')
    <style>
        td input{
            border:none;
            background-color: transparent;
        }
    </style>
<h1>Список запчастей для формирования прайса</h1>
    @php
        $cou = count($input->catnum) + 1;
    @endphp
    <div class="mess">
        @if(Session::has('msg'))
            <div class="alert {{Session::get('file-class')}}">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                </button>
                <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> {{Session::get('msg')}}
            </div>
        @endif
    </div>
    <form id="put_prise" action="{{route('create.list')}}" method="post">
        {{ csrf_field() }}
        <table class="table table-striped ">
            <thead class="table-info">
                <tr>
                    <th>№</th>
                    <th>Каталожный номер детали:</th>
                    <th>количество:</th>
                    <th>Наличие</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 1; $i < $cou; $i++)
            <tr>
                <td>{{$i}}</td>
                <td>
                    <input type="text" name="catnum[{{$i}}]" value="{{isset($input->catnum[$i])? $input->catnum[$i]: ''}}">
                </td>
                <td>
                    <input type="number" name="count[{{$i}}]"
                           value="@php if (!isset($input->count[$i]) || $input->count[$i] == null) echo '1'; else echo $input->count[$i]; @endphp">
                </td>
                <td>
                    {!! $input->status[$i] !!}
                </td>
            </tr>
            @endfor
            </tbody>
        </table>
    </form>
    <div class="">
        {{--start reload --}}
{{--        <div type="button" id="reload_page" class="btn btn-secondary"><i class="fa fa-info-circle text-danger"></i> Если одной или нескольких деталей "нет в каталоге", попробуйте нажать </div>--}}
{{--        <script type="text/javascript">--}}
{{--            $(document).ready(function(){--}}
{{--                $("#reload_page").click(function(){--}}
{{--                    setTimeout(function() {--}}
{{--                            location.reload();--}}
{{--                        }, 1000);--}}
{{--                });--}}
{{--            });--}}
{{--        </script>--}}
        {{-- end reload--}}
    </div>
    <div class="clear_10"></div>
    <p>Если всё правильно:</p>
    <input type="submit" class="btn btn-info" form="put_prise" id="puting_price" style="display:block;margin-left:10px;" value="Сформировать прайс с ценами"/>
    <p>Или:</p>
    <a href="{{route('reserve.price')}}" class="btn btn-warning">Ввести ещё раз</a>
    @include('template.result_seach')
@endsection
