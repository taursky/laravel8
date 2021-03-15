<table class="table table-hover">
    <thead class="table-info">
    <tr>
        <td style="width: 40px">№</td>
        <td>Наименование</td>
        <td>Артикул</td>
        <td>Цена</td>
        <td>Кол-во</td>
        <td>Сумма</td>
    </tr>
    </thead>
    <tbody>
    @if($sale_cart && count($sale_cart) > 0)
        @foreach($sale_cart as $sale)
            @php
                $detal = App\Sale::where('id', $sale->detal_id)->first();
            @endphp
            <tr>
                <td style="width: 40px">{{$i}}</td>
                <td>{{$detal->title}}</td>
                <td>{{$detal->articul}}</td>
                <td style="min-width: 90px">{{number_format(App\Model\PartsModel::finalPrice($detal->price), 0, '.', ' ')}}</td>
                <td>{{$sale->count}}</td>
                <td style="min-width: 100px">{{number_format(App\Model\PartsModel::finalPrice($detal->price) * $sale->count, 0, '.', ' ')}}</td>
            </tr>
            @php
                $sum += App\Model\PartsModel::finalPrice($detal->price) * $sale->count;
                $i++;
            @endphp
        @endforeach
    @endif
    @if($oem_cart && count($oem_cart) > 0)
        @foreach($oem_cart as $oem)
            @php
                $detal = App\Model\PartsModel::getDetalOptions($oem->detal_id);
            @endphp
            <tr>
                <td style="width: 40px">{{$i}}</td>
                <td>{{$detal['name']}}</td>
                <td style="min-width:150px">{{\App\Model\PartsModel::createNiceArticul($detal['articul'], $detal['brand'])}}</td>
                <td>{{number_format(App\Model\PartsModel::finalPrice($detal['prise']), 0, '.', ' ')}}</td>
                <td>{{$oem->count}}</td>
                <td>{{number_format(App\Model\PartsModel::finalPrice($detal['prise']) * $oem->count, 0, '.', ' ')}}</td>
            </tr>
            @php
                $sum += App\Model\PartsModel::finalPrice($detal['prise']) * $oem->count;
                $i++;
            @endphp
        @endforeach
    @endif
    @if($catalog_cart && count($catalog_cart) > 0)
        @foreach($catalog_cart as $catalog)
            @php
                $detal = App\Product::where('id', $catalog->detal_id)->first();
                $prise = App\Model\PartsModel::finalPrice($detal->prise * App\Model\PartsModel::getMarginStorage());
            @endphp
            <tr>
                <td style="width: 40px">{{$i}}</td>
                <td>{{$detal->name}}</td>
                <td>{{$detal->articul}}</td>
                <td style="min-width: 90px">{{number_format($prise, 0, '.', ' ')}}</td>
                <td>{{$catalog->count}}</td>
                <td style="min-width: 100px">{{number_format($prise * $catalog->count, 0, '.', ' ')}}</td>
            </tr>
            @php
                $sum += $prise * $catalog->count;
                $i++;
            @endphp
        @endforeach
    @endif
    </tbody>
</table>
<h3>Всего ( <span style="color: #138496">{{$i-1}}</span> ) на сумму: {{number_format($sum, 0, '.', ' ')}} <i class="fa fa-rub"></i> </h3>
<div class="clear_10"></div>
<div class="">
    <form action="{{route($link)}}" class="mail-form" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="type" value="{{$type}}">
        <table class="table table-secondary table-hover">{{-- table_order_form --}}
            <tr>
                <td>Имя<span style="color: red;">*</span> </td>
                <td><input type="text" class="form-control" name="name" value="{{Auth::user()->name}}" required/></td>
            </tr>
            <tr>
                <td>E-mail<span style="color: red;">*</span></td>
                <td class="{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ old('email')? old('email'):Auth::user()->email}}" >
                    <span class="form_hint">например "name@email.com"</span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Телефон<span style="color: red;">*</span></td>
                <td>
                    <input type="text" name="phone" class="form-control" value="{{Auth::user()->phone}}" required/>
                    <span class="form_hint">например "+7 (999) 444-66-44"</span>
                </td>
            </tr>
            <tr>
                <td>Способ доставки<br>изменить, или подтвердить выбор</td>
                <td>
                    @foreach($delivery as $item)
                        @php

                            if (Session::has('delivery') && Session::get('delivery') == $item->id)
                                $checked = 'checked';
                            elseif (!Session::has('delivery') && $item->id == 1)
                                $checked = 'checked';
                            else
                                $checked = '';
                        @endphp
                        <input type="radio" name="delivery"  value="{{$item->id}}" style="width:40px; height:20px;" {{$checked}} required>
                        {{$item->name}}
                        <br>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>Адрес</td>
                <td><textarea class="form-control" name="adress">{{Auth::user()->address}}</textarea></td>
            </tr>
            <tr>
                <td>Комментарии к заявке</td>
                <td><textarea class="form-control" name="description" value=""></textarea></td>
            </tr>
        </table>
        <br/>
        <input type="submit" name="to_order" class="btn btn-secondary" value="Оформить заказ">
    </form>
    <div class="clear_10"></div>

</div>