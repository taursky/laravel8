@extends('layout.main')
@section('title', 'корзина')
@section('description', 'автозапчасти для японских автомобилей.')
@section('keywords', '')

@section('content')
    <h1 class="text-muted"><i class="fa fa-cart-arrow-down fa-2x"></i> Ваша корзина</h1>
    @php
        $sum_sale = 0;
        $sum_oem = 0;
        $sum_product = 0;
    @endphp
    @if($sale_cart && count($sale_cart) > 0)
        <h3 class="text-center">Заказ Распродажа</h3>
{{--        <table class="table table-hover table_cart">--}}
        <table class="tablesaw tablesaw-stack" data-tablesaw-mode="stack">
            <thead class="table-info">
            <tr>
                <th>№</th>
                <th>Артикул</th>
                <th>Наименование</th>
                <th>Стоимость</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th>Удалить</th>
            </tr>
            </thead>
            <tbody class="text-center">
            @foreach($sale_cart as $sale)
                @php
                    $product = App\Sale::where('id', $sale->detal_id)->first();
                    $sum_sale += $sale->count * App\Model\PartsModel::finalPrice($product->price);
                @endphp
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$product->articul}}</td>
                    <td>{{$product->title}}</td>
                    <td>{{number_format(App\Model\PartsModel::finalPrice($product->price), 0, '.', ' ')}} <i class="fa fa-rub"></i></td>
                    <td>
                        <form action="{{route('refresh.cart')}}" class="no-buttom" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$sale->id}}">
                            <input type="text"  size=3 name="count" value="{{$sale->count}}" style="text-align:center">
                            <button title='обновить кол-во товаров'>
                                <i class="fa fa-refresh fa-lg"></i>
                            </button>
                        </form>
                    </td>
                    <td>{{ number_format(($sale->count * App\Model\PartsModel::finalPrice($product->price)), 0, '.', ' ')}} <i class="fa fa-rub"></i></td>
                    <td>
                        <form action="{{route('delete.cart')}}" class="no-buttom" method="post">
                            {{ csrf_field() }}
                            <input type='hidden' name='id' value="{{$sale->id}}">
                            <button title="удалить">
                                <i class="fa fa-close fa-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>

            @endforeach
            <tr>
                <td colspan='4'></td>
                <td>К оплате: </td>
                <td>
                    <strong> <span style='color: #7F0037'>{{number_format($sum_sale, 0, '.', ' ')}} <i class="fa fa-rub"></i></span></strong>
                </td>
                <td></td>
            </tr>
            </tbody>

        </table>

    @endif
    @if($oem_cart && count($oem_cart) > 0)
        <h3 class="text-center">Для заказа оригинальных запчастей из Японии</h3>

{{--        <table  class="table table-hover table_cart">--}}
        <table class="tablesaw tablesaw-stack" data-tablesaw-mode="stack">
            <thead class="table-info text-center">
            <tr>
                <th>№</th>
                <th>Артикул</th>
                <th>Наименование</th>
                <th>Стоимость</th>
                <th>Количество</th>
                <th>Доставка</th>
                <th>Сумма с доставкой</th>
                <th>Удалить</th>
            </tr>
            </thead>
            <tbody class="text-center">
            @foreach($oem_cart as $oem)
                @php
                    $detal = App\Model\PartsModel::getDetalOptions($oem->detal_id);
                    $sum_oem += $oem->count * App\Model\PartsModel::finalPrice($detal['prise']);
                @endphp
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td style="min-width: 140px">{{\App\Model\PartsModel::createNiceArticul($detal['articul'],$detal['brand'])}}</td>
                    <td>{{$detal['name']}}</td>
                    <td>{{number_format(App\Model\PartsModel::finalPrice($detal['prise']), 0, '.', ' ')}} <i class="fa fa-rub"></i></td>
                    <td>
                        <form action="{{route('refresh.cart')}}" class="no-buttom" method="post">
                            {{ csrf_field() }}
                            <input type="text" style="text-align:center" size=3 name="count" value="{{$oem->count}}">
                            <input type="hidden" name="id" value="{{$oem->id}}">
                            <button title="обновить кол-во товаров">
                                <i class="fa fa-refresh fa-lg"></i>
                            </button>
                        </form>
                    </td>
                    <td>10 дн.</td>
                    <td style='min-width: 110px'>{{ number_format(($oem->count * App\Model\PartsModel::finalPrice($detal['prise'])), 0, '.', ' ')}} <i class="fa fa-rub"></i></td>
                    <td>
                        <form action="{{route('delete.cart')}}" class="no-buttom" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$oem->id}}">
                            <button title="удалить">
                                <i class="fa fa-close fa-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td colspan="2">К оплате: </td>
                <td>
                    <strong> <span style='color: #7F0037'>{{number_format($sum_oem, 0, '.', ' ')}} <i class="fa fa-rub"></i></span></strong>
                </td>
                <td></td>
            </tr>
            </tbody>
        </table>

    @endif
    @if($product_cart && count($product_cart) > 0)
        <h3 class="text-center">Заказ со склада во Владивостоке</h3>
{{--        <table class="table table-hover table_cart">--}}
        <table class="tablesaw tablesaw-stack" data-tablesaw-mode="stack">
            <thead class='table-info'>
            <tr><th>№</th>
                <th>Артикул</th>
                <th>Наименование</th>
                <th>Стоимость</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th>Удалить</th>
            </tr>
            </thead>
            <tbody class="text-center">
            @foreach($product_cart as $product)
                @php
                    $sklad = App\Product::where('id', $product->detal_id)->first();
                    $prise = App\Model\PartsModel::finalPrice($sklad->prise * $nas);
                    $sum = $product->count * $prise;
                    $sum_product += $sum;
                @endphp
                <tr>
                    <td >{{$loop->iteration}}</td>
                    <td >{{$sklad->articul}}</td>
                    <td>{{$sklad->name}}</td>
                    <td>{{number_format($prise, 0, '.', ' ')}} <i class="fa fa-rub"></i></td>
                    <td >
                        <form action="{{route('refresh.cart')}}" class="no-buttom" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$product->id}}">
                            <input type="text" size=3 name="count" value="{{$product->count}}" style="text-align:center">
                            <button title="обновить кол-во товаров" >
                                <i class="fa fa-refresh fa-lg"></i>
                            </button>
                        </form>
                    </td>
                    <td style='min-width: 90px'>{{number_format($sum, 0, '.', ' ')}} <i class="fa fa-rub"></i></td>
                    <td>
                        <form action="{{route('delete.cart')}}" class="no-buttom" method="post">
                            {{ csrf_field() }}
                            <input type='hidden'name='id' value="{{$product->id}}">
                            <button title="" ><i class="fa fa-close fa-lg"></i> </button>
                        </form>
                    </td>
                </tr>

            @endforeach
            <tr>
                <td colspan='4'></td>
                <td>К оплате: </td>
                <td>
                    <strong> <span style='color: #7F0037'>{{number_format($sum_product, 0, '.', ' ')}} <i class="fa fa-rub"></i></span></strong>
                </td>
                <td></td>
            </tr>
            </tbody>
        </table>
    @endif

    @if((!$sale_cart || count($sale_cart) == 0)&& (!$oem_cart || count($oem_cart) == 0) && (!$product_cart || count($product_cart) == 0))
        <h2 class="text-danger">Вы ничего не заказали и ваша корзина пуста</h2>
    @endif

    @if(($sale_cart && count($sale_cart) > 0) || ($oem_cart && count($oem_cart) > 0) || ($product_cart && count($product_cart) > 0))
        <div class="total-summ-cart">
            <br> всего : {{number_format($sum_sale + $sum_oem + $sum_product, 0, '.', ' ')}} <i class="fa fa-rub"></i><br>
        </div>



        <form action="{{route('order.email')}}" method="post">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-info" title="Оформить заказ отправив письмо на Email">
                Оформить заказ по почте
            </button>
        </form>
        <div class="clear_10"></div>
        <form action="{{route('order.own')}}" method="post">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-info" title="При оплате учитывается комиссия в размере ?? %">
                Оформить заказ с личного счета
            </button>
        </form>
        <div class="clear_10"></div>
        {{--
                    <form action="{{route('order.kosh')}}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-secondary" title="">
                            оплатить через "Единый кошелек"
                        </button>
                    </form>
                    <form action="{{route('order.yandex')}}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-secondary" title="">
                            Оплатить на Yandex кошелек
                        </button>
                    </form>
                    <form action="{{route('order.sber')}}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-secondary" title="">
                            Оплатить на карту Сбербанк
                        </button>
                    </form>
        --}}

    @endif

@endsection
