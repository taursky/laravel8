<div class="row" style="margin: 0 0.05%;padding: 12px 8px;">
    <div class="col-lg-6 col-md-12 col-sm-12 text-center" style="margin: 0;padding: 12px;background-color: rgba(249,249,249,0.44);box-shadow: 0 0 10px rgba(187,187,187,0.5);">
        <form method="get" action="{{route('search.storage')}}" id="search-storage" class="text-info">
            <label for="view-detal-sclad">
                <p style="color:#2B0B02 ; font-weight:bold;font-size:16px; padding-top:12px;">
                    Введите номер из каталога, или наименование запчасти для поиска детали на складе
                </p>
            </label>
            <input type="text" name="part"  size="" class="form-control" value="{{isset($request->part)?$request->part:''}}" placeholder="артикул или наименование"><br>
            <div class="clear_10"></div>
            <input id="submit" type="submit" class="btn btn-info" value="Найти"><br>
        </form>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 text-left" style="padding: 16px;background-color: rgba(241,241,241,0.44);box-shadow: 0 0 10px rgba(179,179,179,0.5);">
            <h5 class="text-center">Условия поиска</h5>
            <div class="form-check">
                <input type="checkbox" form="search-storage" id="strong_check" class="form-check-input" name="strong" {{isset($request->strong)?'checked':''}}>
                <label for="strong">Строгий поиск</label>
            </div>
            <div class="form-check" id="brand_box">
                <input type="checkbox" form="search-storage" id="brand-check" class="form-check-input" name="brand" {{isset($request->brand)?'checked':''}}>
                <label for="brand">Искать детали только определенной марки</label>
                <div class="only-brand">
                <select class="form-control" form="search-storage" name="brand_lable">
                    <option value="0">Выбрать марку</option>

                    @foreach($brands as $brand)
                        @if(isset($request->brand_lable) && $request->brand_lable == $brand)
                            @php
                                $selected = 'selected';
                            @endphp
                        @else
                            @php
                                $selected = '';
                            @endphp
                        @endif
                        <option value="{{$brand}}" {{$selected}}>{{$brand}}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div id="search-group">
                <div class="form-check">
                    <input type="checkbox" form="search-storage" class="form-check-input" name="name" {{isset($request->name)?'checked':''}}>
                    <label for="name">Искать только по названию</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" form="search-storage" class="form-check-input" name="articul" {{isset($request->articul) && !isset($request->name)?'checked':''}}>
                    <label for="name">Искать только по артикулу</label>
                </div>
            </div>
    </div>
    <div class="clear"></div>
</div>
