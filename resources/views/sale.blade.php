@extends('layout.main')
@section('title', 'Распродажа')
@section('description', '')
@section('keywords', '')
@section('content')
    <h1 style="text-align: center">Распродажа</h1>
    @if($sales)
{{--        <table class="table table-hover table_japan">--}}
        <table class="tablesaw tablesaw-stack" data-tablesaw-mode="stack">
            <thead class="table-info">
            <tr>
                <th>производитель</th>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Артикул</th>
                <th>Количество</th>
                <th></th>
            </tr>
            </thead>
            @foreach ($sales as $detal)
                @php
                    $price_detal = number_format(App\Model\PartsModel::finalPrice($detal->price), 0, ".", " ");
                    Auth::check()?$u_id = Auth::user()->id:$u_id = null;
                @endphp


                    <tr>
                        <td >{{$detal->brand}}</td>
                        <td >{{ $detal->title }}</td>
                        <td >{{ $price_detal }} <i class="fa fa-rub"></i></td>
                        <td >{{ $detal->articul }}</td>
                        <td >{{ $detal->count }} </td>
                        <td style="position:relative;">
                            <form action="" id="put-cart" class="form_add_to_cart" method="post">{{--/put/cart--}}
                                <input type="hidden"  id="count-to-cart{{$detal->id}}" class="count" name="count" value="1">
                                <input type="hidden" class="product_type" name="type" value="3">
                                <input type="hidden" class="product_id" name="id" value="{{$detal->id}}">
                                <input type="hidden" class="id_user" name="idu" value="{{ $u_id }}">
                                <button class="put-cart" style="border: none;background-color: transparent;cursor: pointer">
                                    <i class="fa fa-cart-arrow-down fa-2x" aria-hidden="true"></i>
                                </button>
                                <div class="cart_send" style="position:absolute;top:0;left:0;width:100%;height:100%;padding:4px;display: none;background-color: #ffffff;">
                                    <i class="fa fa-cart-plus fa-3x text-danger " aria-hidden="true"></i>
                                </div>
                            </form>
                        </td>
                    </tr>

            @endforeach
        </table>
        <br><br>
    @else
        <h3>На данный момент нет запчастей на распродажу</h3>
    @endif
@endsection
