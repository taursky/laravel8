@extends('layout.main')
@section('title', 'Результат поиска')
@section('description', '')
@section('keywords', '')
@section('content')
    <div id="mess"></div>
    @php
        Auth::check()?$idu = Auth::user()->id:$idu = null;
    @endphp
    @if($error)
        {!! $error !!}
    @else
    <p>Вы ищете:&nbsp;"<b style="color: #28a1c7">{{$articul}}</b>".
    @endif
    @if($result_detals && count($result_detals) > 0)

        <div class="cart-block">
            <h3>Оригинальные запчасти (заказ из японии) <span style="font-size: 18px;">(нашли:&nbsp;{{$count_oem}})</span></h3><br>
            {{-- TODO form Для отправки в корзину!! --}}
{{--            <table class="table table-hover table_japan">--}}{{-- table-responsive  table_japan --}}
            <table class="tablesaw tablesaw-stack" data-tablesaw-mode="stack">{{-- table-responsive  table_japan --}}
                <thead class="table-info">
                <tr>
                    <th>Марка</th>
                    <th>Название</th>
                    <th>Вес</th>
                    <th>Срок</th>
                    <th>Цена</th>
                    <th>Артикул</th>
                    <th>Кол-во</th>
                    <th></th>
                </tr>
                </thead>

                    @foreach($result_detals as $detales)
                        @foreach($detales as $detal)
                        @php
                            //TODO наименование в модель
                            if (isset($detal->name_ru) && $detal->name_ru != '')$name_detal = $detal->name_ru;
                            elseif (isset($detal->name_in) && $detal->name_in != '')$name_detal = $detal->name_in;
                            elseif (isset($detal->name_jp) && $detal->name_jp != '')$name_detal = $detal->name_jp;
                            else $name_detal = 'деталь';
                            $weight = $detal->weight != 0?$detal->weight: 'стоимость доставки уточнять при заказе';
                            $price_detal = App\Model\PartsModel::getPriceOemDetal($detal->id);
                            $price_detal = App\Model\PartsModel::finalPrice($price_detal);
                        @endphp

                        <tr>
                            <td> {{$detal->brand}}</td>
                            <td>{{$name_detal}}</td>
                            <td>{{$weight}}</td><!--вес детали-->
                            <td>10 дн.</td><!--srok postavki-->
                            <td style="min-width: 90px">{{number_format($price_detal, 0, '.', ' ')}} р.</td>
                            <td style="min-width: 110px">{{\App\Model\PartsModel::createNiceArticul($detal->articul, $detal->brand)}}</td>
                            <td><input type="number" form="send-to-cart{{$detal->id}}" id="count-to-cart{{$detal->id}}" class="count" name="count" value="1" style="max-width: 35px"></td>
                            <td style="position:relative;">
                                <form action="" id="send-to-cart{{$detal->id}}" class="form_add_to_cart" method="post">{{--/put/cart--}}
                                    <input type="hidden" class="product_type"  name="type" value="1">
                                    <input type="hidden" class="product_id" name="id" value="{{$detal->id}}">
                                    <input type="hidden" class="id_user" name="idu" value="{{ $idu }}">
                                    <button class="put-cart" style="border: none;background-color: transparent;cursor: pointer">
                                        <i class="fa fa-cart-arrow-down fa-2x" aria-hidden="true"></i>
                                    </button>
                                    <div class="cart_send" style="position:absolute;top:0;left:0;width:100%;height:100%;display: none;background-color: #ffffff;">
                                        <i class="fa fa-cart-plus fa-3x text-danger " aria-hidden="true"></i>
                                    </div>
                                </form>
                            </td>
                        </tr>

                            @endforeach
                    @endforeach

            </table>
        </div>
    @else
        {{--}}@if()
        @else--}}
        <h3>Не нашлось детали</h3>
        <div class="clear_10"></div>
        @include('template.result_seach')
        {{--}}@endif--}}
    @endif
    @if($result_sklad && count($result_sklad) > 0)
        {{-- Со склада !!!!!!  --}}
        <div class="clear_20"></div>
        <div class="cart-block">
            <h3>Запчасти имеющиеся на складе <span style="font-size: 18px;">(нашли:&nbsp;{{$count_sklad}})</span></h3><br>
{{--            <table class="table table-hover table_japan">--}}{{-- table-responsive  table_japan --}}
            <table class="tablesaw tablesaw-stack" data-tablesaw-mode="stack">
                <thead class="table-info">
                <tr>
                    <th>Марка</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Артикул</th>
                    <th></th>
                </tr>
                </thead>
                @foreach($result_sklad as $detals)
                    @foreach($detals as $detal)
                        @php
                            //TODO Цена умноженная на наценку
                        $price_detal = $detal->prise * \App\Model\PartsModel::getMarginStorage();
                        @endphp

                        <tr>
                            <td style="width:50px;"> {{$detal->brand}}</td>
                            <td style="">{{$detal->name}}</td>
                            <td style="width:150px;">{{number_format(App\Model\PartsModel::finalPrice($price_detal), 0, '.', ' ')}} р.</td>
                            <td style="width:100px;">{{$detal->articul}}</td>
                            <td style="position:relative;width:60px;">
                                <form action="" class="form_add_to_cart" method="post">{{--/put/cart--}}
                                    <input type="hidden"  id="count-to-cart{{$detal->id}}" class="count" name="count" value="1">
                                    <input type="hidden" class="product_type"  name="type" value="2">
                                    <input type="hidden" class="product_id" name="id" value="{{$detal->id}}">
                                    <input type="hidden" class="id_user" name="idu" value="{{ $idu }}">
                                    <button class="put-cart" style="border: none;background-color: transparent;cursor: pointer">
                                        <i class="fa fa-cart-arrow-down fa-2x" aria-hidden="true"></i>
                                    </button>
                                    <div class="cart_send" style="position:absolute;top:0;left:0;width:100%;height:100%;display: none;background-color: #ffffff;">
                                        <i class="fa fa-cart-plus fa-3x text-danger " aria-hidden="true"></i>
                                    </div>
                                </form>
                            </td>
                        </tr>

                    @endforeach
                @endforeach
            </table>
        </div>
    @endif
@endsection
