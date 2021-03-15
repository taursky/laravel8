@extends('layout.main')
@section('title', 'Запчасти на складе')
@section('description', '')
@section('keywords', '')
@section('content')
    <style>
        .w-5{
            width: 15px;
        }
    </style>
    <h1>Список запчастей имеющихся на складе</h1>
    @include('form.poisk_sclad', ['brands' => $brands])
    <div class="table_catalog">
{{--        <table class="table table-hover table-sclad">--}}
        <table class="tablesaw tablesaw-stack" data-tablesaw-mode="stack">
            <thead class="table-info">
                <tr>
                    <th>Артикул</th>
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Цена <i class="fa fa-rub"></i></th>
                    <th> </th>
                </tr>
            </thead>
            @foreach($catalog as $item)
                @php
                    $prise = $item->prise * $nas;//1.1;
                    Auth::check()?$u_id = Auth::user()->id:$u_id = null;
                @endphp

                <tr>
                    <td align="center">{{$item->articul}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->balance}}</td>
                    <td style="font-weight: 500">{{number_format(App\Model\PartsModel::finalPrice($prise), 0, '.', ' ')}}</td>
                    <td style="position:relative;text-align:left;width:60px">
                        <form action="" class="form_add_to_cart"  method="post">
                            <input type="hidden"  id="count-to-cart{{$item->id}}" class="count" name="count" value="1">
                            <input type="hidden" class="product_type" name="type" value="2">
                            <input type="hidden" class="product_id" name="id" value="{{$item->id}}">
                            <input type="hidden" class="id_user"name="idu" value="{{ $u_id }}">
                            <button class="put-cart" style="border: none;background-color: transparent;cursor: pointer">
                                <i class="fa fa-cart-arrow-down fa-2x" aria-hidden="true"></i>
                            </button>
                            <div class="cart_send" style="position:absolute;top:0;left:0;width:100%;height:100%;padding:4px;display: none;background-color: #ffffff;">
                                <i class="fa fa-shopping-cart fa-3x text-danger" aria-hidden="true"></i>
                            </div>
                        </form>
                    </td>
                </tr>

            @endforeach
        </table>
        <div class="own-pagination">
            {{$catalog->render()}}
        </div>
    </div>
    <div class="clear_20"></div>

@endsection
