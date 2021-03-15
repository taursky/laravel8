@extends('layout.main')
@section('content')
    <style>
        /*.container{
            background-color: rgba(255, 255, 255, 0.94);
            background-image: url("../../../images/style/35.gif");
            background-position: center;
            background-repeat: no-repeat;
        }*/
        .popup-window{
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(237, 237, 237, 0.68);
            background-image:  url("../../../images/style/55.gif");
            background-position: center;
            background-repeat: no-repeat;

            z-index: 1000;
        }
    </style>

<form method="post" id="redirect-articul" action="{{route('search.oem')}}" style="display: none">
    {{ csrf_field() }}
    <input type="text" name="oem_zapch"  size="80" value="{{ $request['oem_zapch'] }}">
    <br>
{{--<button type="submit" class="search-button">Найти</button>--}}
</form>
    <div class="popup-window"></div>
    <h3 class="text-center">Пожалуйста подождите идет поиск детали ...</h3>
<script type="text/javascript">
     count = {{$count}} * 1000;
    //document.getElementById('form-oem').style('display', 'none');
    $('.rezOemPoisk').hide();
   setTimeout(function () {
       document.getElementById('redirect-articul').submit();
   }, count);
</script>
@endsection
