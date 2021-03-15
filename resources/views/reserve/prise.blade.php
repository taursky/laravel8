@extends('layout.main')
@section('title', 'Загрузить прайс')
@section('description', '')
@section('keywords', '')
@section('content')
    <style>
        td input{
            border:none;
            background-color: transparent;
        }
    </style>
    <h1>Форма заказа запчастей </h1>

    <div class="clear_10"></div>
    <div id="form_put">
        <form id="put_prise" action="{{route('make.price')}}" method="post">
            {{ csrf_field() }}

            <table id="table_parts" class=" table part_revers">
                <thead class="table-secondary">
                <th>№</th>
                <th><span class="red-star">* </span>Каталожный номер детали:</th>
                <th><span class="red-star">* </span>количество:</th>
                </thead>
                <tbody>
                <tr>
                    <td class="loop_0">
                        0
                    </td>
                    <td>
                        <input type="text" id="num"  value="">
                    </td>
                    <td>
                        <input type="text" id="cou"  value="1" required>
                    </td>
                </tr>

                </tbody>
            </table>
                    <input type="submit" class="btn btn-success" id="put_more"  style="display:block;" value="Добавить артикул">
        </form>
    </div>
    <br>
    <input type="submit" id="send-to-table" class="btn btn-info" form="put_prise"  style="margin-left:10px;" value="Отправить">
    {{--<button type="submit" class="btn btn-info" form="put_prise" id="otpavit_price" name="otpavit_price" style="display: none;"> Сформировать прайс с ценами </button>--}}

    @include('template.result_seach')

    <div class="">
        <div class="popwindow">
            <div class="title_popwindow">
                <p style="background:url({{asset('/images/icons/page_white_excel.png')}}) no-repeat;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Можно  загрузить прайс с каталожными номерами в формате .xls, .xlsx&nbsp;&nbsp;
                    <span>
                        <i class="fa fa-info-circle text-danger" onmouseover="helpDounload();"  onmouseout="hideHelpDounload();" alt="Подсказка по загрузке файла excel"></i>
                    </span>
                </p>
            </div>
        </div>
        <div id="help_text" class="help_text">Файл excel должен содержать два столбца артикул и количество запчастей</div>
    </div>
    <div class="clear clear_10"></div>
    <div class="mess">
        @if(Session::has('msg'))
            <div class="alert {{Session::get('file-class')}}">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                </button>
                <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> {{Session::get('msg')}}
            </div>
        @endif
    </div>

    <div class="clear clear_10"></div>
    <hr>
    <div class="col-5">

        <form id="prise_detal" method="post" enctype="multipart/form-data" action="{{route('upload.price')}}" onsubmit="hideBtn();">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('prise_xls') ? ' has-error' : '' }}">
                <label for="file-upload" class="text-center text-info">Загрузить файл с номерами деталей и количеством</label>
                <input type="file" id="file-upload"  name="prise_xls" value="">
                @if ($errors->has('prise_xls'))
                    <span class="help-block">
                        <strong>{{ $errors->first('prise_xls') }}</strong>
                    </span>
                @endif
            </div>
            <br><br>
            <input name="save_prise" type="submit" class="btn btn-info" title="Загрузить список" value="Загрузить список"/>
        </form>
    </div>

    <div id="res"></div>
    <iframe id="hiddenframe" name="hiddenframe" style="width:0px; height:0px; border:0px"></iframe>
    <script type="text/javascript">
        function helpDounload(){
            document.getElementById('help_text').style.display="block";
        }
        function hideHelpDounload(){
            document.getElementById('help_text').style.display="none";
        }
    </script>

@endsection
