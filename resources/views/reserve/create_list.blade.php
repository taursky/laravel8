@extends('layout.main')
@section('title', 'Загрузить прайс')
@section('description', '')
@section('keywords', '')
@section('content')
    @if($arr_xls)
        @if($is_company == null)
            <p class="text-center">Для получения оптовой скидки, Вам необходимо заполнить реквизиты компании в личном кабинете и обратиться к менеджеру через контакты.</p>
        @else
            <h2>Ваша оптовая скидка {{$is_company->discount}} % </h2><br>
            <p style="font-size:14px;font-weight:bold;">Сумма указана с учетом оптовой скидки и стоимостью доставки (если вес не равен 0).</p>
        @endif
        <div class="clear_10"></div>
        <form action="{{route('create.xls.file')}}" method="POST">
            {{ csrf_field() }}
            <table class="table table-hover table-responsive table_japan">
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
                    $i = 0;
                    $dollar = \App\Model\PartsModel::getDollar();
                    $all_sum_xls = null;
                @endphp
                @foreach($arr_xls as $xls_tab)
                    @php
                        $weight_detal = $xls_tab['weight'];
                        $name_detal = $xls_tab["name_detal"];
                        $nnn = $i+1;
                        $weight_detal = str_replace(",",".",$weight_detal);
                        if ($weight_detal == 0){
                            $sum_dost = 'без стоимости доставки';
                        }
                        else {
                            $sum_dost = (float)$weight_detal * 7 * $dollar;
                            $sum_dost = round($sum_dost,2);
                        }
                        $prise_xls = \App\Model\PartsModel::getPriceOemDetal($xls_tab['id']);
                        $sum_xls = ($prise_xls) * $xls_tab["count_det"];
                        $all_sum_xls += (float)$sum_xls;
                        $arr_xls_make[$i]=[
                            "nom" => $nnn,
                            "cod_zapch" => $xls_tab["cod_zapch"],
                            "produser" => $xls_tab["produser"],
                            "name" => $name_detal,
                            "weight" => $weight_detal,
                            "count" => $xls_tab["count_det"],
                            "sum_dost" => $sum_dost,
                            "price" => $prise_xls,
                            "all_sum" => round($sum_xls,2),
                        ];//массив для формирования файла Excel
                    @endphp
                <input type="hidden" name="detal_array" value="{{json_encode ($arr_xls_make)}}">

                    <tr>
                        <td>{{$nnn}}</td>
                        <td>{{$xls_tab["cod_zapch"]}}  </td>
                        <td>{{$xls_tab["produser"]}}</td>
                        <td>{{$name_detal}}</td>
                        <td>{{$weight_detal}}</td>
                        <td>{{$xls_tab["count_det"]}}</td>
                        <td>{{$sum_dost}}</td>
                        <td style="min-width: 90px">{{number_format(floatval($prise_xls), 2, '.', ' ')}}</td>
                        <td style="min-width: 95px">{{number_format(floatval($sum_xls), 2, '.', ' ')}}</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
                    <tr>
                        <td colspan="8" style="text-align:right;padding-right:15px;"><b> Сумма : </b></td>
                        <td>{{number_format($all_sum_xls, 2, '.', ' ')}}</td>
                    </tr>
            </table>
            <br>

            <input type="submit" class="btn btn-success" name="download_xls" value="Сформировать файл .xlsx"/>
        </form>
    @else
        <h3>Не нашлось детали</h3>
        @include('template.result_seach')
    @endif
    <br><br>
    <a href="{{route('reserve.price')}}" class="btn btn-info"><b>Попробовать ещё.</b></a>
    <div class="clear_20"></div>
@endsection
