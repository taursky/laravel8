@extends('layout.main')
@section('title', 'Результат поиска')
@section('description', '')
@section('keywords', '')
@section('content')
    <h2>Результат поиска деталей на складе</h2>
            @if(!$items || $items->total() == 0)
                @if($empty)
                    <div class="price">
                        <p><strong style="color: red">{{$empty}}</strong></p>
                        @include('form.poisk_sclad' , ['brands' => $brands, 'request' => $request])
                    </div>
                @else
                    <div class="price">
                        <p><strong>Вы пытались найти:<span style="color:darkred">"{{$request->part}}" {{isset($request->brand)? 'марки '.$request->brand_lable: ''}}</span>
                                , Но на складе такого нет, попробуйте поискать на заказ или введите другие критерии поиска:</strong></p>
                        @include('form.poisk_sclad' , ['brands' => $brands, 'request' => $request])
                    </div>
                @endif
            @else
            <div class="">
                <strong>Ваш запрос:  "<span style="color: #479bc7;font-weight: 700;">{{$request->part}} {{isset($request->brand)? 'марки '.$request->brand_lable: ''}}</span>" нашлось (<span style="color: red;">{{$items->total()}}</span>)</strong>

            </div>
            <div class="clear_20"></div>
            @include('form.poisk_sclad', ['brands' => $brands, 'request' => $request])
            <div class="table_catalog" >
                <table class="table table-hover table-sclad">
                    <thead class="table-info">
                    <tr>
                        <th>Артикул</th>
                        <th>Наименование</th>
                        <th>Кол-во</th>
                        <th>Цена <i class="fa fa-rub"></i></th>
                        <th></th>
                    </tr>
                    </thead>
                    @foreach($items as $item)
                        @php
                            $prise = $item->prise * $nas;//1.1;// Из базы
                            Auth::check()?$u_id = Auth::user()->id:$u_id = null;
                        @endphp


                    <tr>
                        <td width="110px"align="center">{{$item->articul}}</td>
                        <td style=" padding: 2px 7px;">{{$item->name}}</td>
                        <td align="center" width="80px">{{$item->balance}}</td>
                        <td style="font-weight:600;width: 90px;"> {{number_format(App\Model\PartsModel::finalPrice($prise), 0,'.', ' ')}}</td>
                        <td style="position:relative;text-align:left;width:50px">
                            <form action="" class="form_add_to_cart"  method="post">
                                <input type="hidden"  id="count-to-cart{{$item->id}}" class="count" name="count" value="1">
                                <input type="hidden" class="product_type" name="type" value="2">
                                <input type="hidden" class="product_id" name="id" value="{{$item->id}}">
                                <input type="hidden" class="id_user"name="idu" value="{{ $u_id }}">
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
                <br>
            </div>
                <div class="own-pagination">
                    {{$items->appends(['part' => $request->part, 'strong' => $request->strong, 'brand' => $request->brand, 'brand_lable' => $request->brand_lable, 'name' => $request->name, 'articul' => $request->articul])->links()}}
                </div>
            @endif
@endsection
