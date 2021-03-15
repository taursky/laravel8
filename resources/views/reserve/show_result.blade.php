@extends('layout.main')
@section('title', 'Загрузить прайс')
@section('description', '')
@section('keywords', '')
@section('content')

    <h1>Содержимое файла <span style="color: #138496">{{$filename}}</span></h1>
<hr>
    <table class="table table-hover table_japan">
        <thead class="table-info">
        <tr>
            <th>№</th>
            <th>Артикул</th>
            <th>Марка</th>
            <th>Название</th>
            <th>Вес детали</th>
            <th>Кол-во</th>
            <th>Доставка</th>
            <th>Цена <i class="fa fa-rub"></i></th>
            <th>Сумма <i class="fa fa-rub"></i></th>
        </tr>
        </thead>
        @php
             $dollar = \App\Model\PartsModel::getDollar();
             $all_sum_xls = 0;
             $detal = json_decode($request['detal_array']);
        @endphp
        @foreach($detal as $xls_tab)
            @php
            $all_sum_xls += $xls_tab->all_sum;
            @endphp
            <tr>
                <td>{{$xls_tab->nom}}</td>
                <td>{{$xls_tab->cod_zapch}}  </td>
                <td>{{$xls_tab->produser}}</td>
                <td>{{$xls_tab->name}}</td>
                <td>{{$xls_tab->weight}}</td>
                <td>{{$xls_tab->count}}</td>
                <td>{{$xls_tab->sum_dost}}</td>
                <td style="min-width: 90px">{{number_format(floatval($xls_tab->price), 2, '.', ' ')}}</td>
                <td style="min-width: 95px">{{number_format(floatval($xls_tab->all_sum), 2, '.', ' ')}}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="8" style="text-align:right;padding-right:15px;"><b> Сумма : </b></td>
            <td>{{number_format($all_sum_xls, 2, '.', ' ')}}</td>
        </tr>
    </table>

    <div class="clear_20"></div>
    <hr>
    <form action="{{route('download.price')}}" method="post">
        {{csrf_field()}}
        <input type="text" name="filename" value="{{$filename}}" style="border: none" readonly><br><br>
        <input type="submit" class="btn btn-warning" value="Скачать прайс">
    </form>
<div class="clear_40"></div>

@endsection
