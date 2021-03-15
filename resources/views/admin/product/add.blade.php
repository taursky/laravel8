@extends('layout.admin')
@section('content')
    <div style="color:#0b7f4d;width: 100%;float: left">
        <h3>Управление запчастями на складе</h3>
    </div>
    <div class="clear"></div>
    <div class="mess">
        @if(Session::has('msg'))
            <div class="alert {{Session::get('file-class')}}">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times"
                                                                       aria-hidden="true"></i></span>
                </button>
                <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> {{Session::get('msg')}}
            </div>
        @endif
    </div>

    <table class="table table-hover text-center" style="width: 500px">
        <thead class="table-info">
        <tr>
            <th>№</th>
            <th>Имя</th>
            <th>Кол-во</th>
            <th>Удалить список</th>
        </tr>
        </thead>
        @foreach ($products as $key => $postName)
            @php
                $count = \App\Product::where('provider', $postName->provider)->count();
            @endphp
            <tr id="">
                <td>{{$loop->iteration}}</td>
                <td width="100px" align="center" id="id"
                    class="detal">{{$postName->provider}}
                </td>
                <td style="min-width: 150px"> ( {{number_format($count, 0, '.', ' ')}} )</td>
                <td>
                    <form action="{{route('delete.provider')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="provider" value="{{$postName->provider}}">
                        <input type="submit" class="btn btn-info"
                               title="Удаление из базы всех товаров поставщика {{$postName->provider}}"
                               onclick="sendform()" value="Удалить товары Поставщика">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <a href="#" rel="truncate" name="truncate" class="btn btn-outline-danger"
       title="Удаляет из таблицы все товары и полностью очищает таблицу">
        Очистить таблицу и сбросить счетчик
    </a>
    <div class="clear_20"></div>

    <a href="#" rel="load_new_catalog" class="btn btn-outline-info"
       title="Загрузить файл xls, xlsx с товарами на сервер">
        <i class="fa fa-file-archive-o fa-2x"></i> &nbsp;Загрузить файл с прайсом на сервер</a>
    <br>

    @php
        $file_names = Storage::disk('catalog')->files();
    @endphp
    <div class="clear_10"></div>
    <table class="table table-hover text-center" style="width: 706px">
        @if($file_names)
            <thead class="table-info">
            <tr>
                <th>№</th>
                <th>Имя файла</th>
                <th>Загрузить</th>
                <th>Удалить</th>
                <th>Посмотреть</th>
            </tr>
            </thead>
            <tr>
                <td colspan="5" style="font-size: 13px">Файлы можно удалить (если прайс не актуальный), или загрузить
                    новый в базу данных!
                </td>
            </tr>

            @foreach($file_names as $fileName)
                <tr id="cat{{$loop->iteration}}">
                    <td>{{$loop->iteration}}</td>
                    <td width="100px" align="center" id="cat{{$loop->iteration}}" class="detalSklad">{{$fileName}}</td>
                    <td width="200px" align="center">
                        @php
                            $nameOwn = explode('_', $fileName);
                            $nameOwn = $nameOwn[0];
                        @endphp
                        @if($nameOwn != 'Elkin')
                            <form action="{{route('upload.catalog')}}" id="update_db" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="filename"
                                       value="{{ $fileName}}">{{--'/files/prises/' .   public_path()--}}
                                <input type="submit" id="submit_update_db" value="Загрузить в базу" onclick="sendform()"
                                       class="btn btn-outline-info">
                            </form>
                        @else
                            <p class="text-danger text-center">файл загружается автоматически</p>
                        @endif
                    </td>
                    <td width="200px" align="center">
                        <form action="{{route('delete.catalog')}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="filename" value="{{$fileName}}">
                            <input type="submit" value="Удалить с сервера" class="btn btn-outline-danger" title="Файл будет физически удален с сервера">
                        </form>
                    </td>
                    <td>
                        <a href="{{Storage::disk('catalog')->path($fileName)}}" class="btn btn-outline-secondary">Скачать</a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">Нет файлов</td>
            </tr>
        @endif
    </table>
    <div class="clear_10"></div>
    <div class="open-desc" onclick="showDescr()" title="Показать информацию для заполнения Excel файла">
        <i class="fa fa-info-circle fa-2x red"></i>
        Информация для заполнения Excel файла
    </div>
    <div class="close-desc" onclick="hideDesc()" title="Закрыть">
        <i class="fa fa-times-circle fa-2x red"></i>
        Скрыть информацию
    </div>
    <div class="desc-import">
        <p style="font-size: 16px;">файл должен содежать поля:<br>
            1: product_id- артикул или внутренний учетный номер <br>
            2: name- описание, или наименование<br>
            3: brand- бренд, или изготовитель<br>
            4: articul- каталожный номер<br>
            5: balance- остаток<br>
            6: prise- цена руб<br>
            7: provider- поставщик<br>
            в таблице excel в первой строке вставляем заголовки(лучше скопировать),
            можно в любом порядке, главное чтобы заголовки соответствовали столбцам. Удаляем лишние столбцы. <b>Готово
                можно грузить на сервер</b>.
        </p>
    </div>
    <div class="truncate-table">
        <div class="popwindow">
            <div class="close_popwindow"><a href="#" rel="cancel_truncate-table"><i
                        class="fa fa-times-circle fa-lg"></i></a></div>
            <div class="title_popwindow">Очистить таблицу</div>

        </div>
        <p style="color: red"><b>Внимательно все данные будут удалены!</b></p>
        <form action="{{route('truncate.table')}}" method="post">
            {{ csrf_field() }}
            <input type="submit" value="Очистить" onclick="sendform()" class="btn btn-info">
        </form>
    </div>
    {{--################################## Загрузка каталога #############################################################################################--}}
    <div class="load_catalog">
        <div class="popwindow">
            <div class="close_popwindow">
                <a href="#" rel="cancel_load_new_catalog" class="">
                    <i class="fa fa-times-circle fa-lg"></i>
                </a>
            </div>
            <div class="title_popwindow">
                Загрузка прайс Поставщика <br>(файлы в формате xls,xlsx)
            </div>
            <div class="clear_20"></div>
        </div>

        <div class="btn_cansel_load_img">
            <form method="post" enctype="multipart/form-data" action="{{route('upload.new.catalog')}}">
                {{ csrf_field() }}
                <input type="file" name="catalog" id="upload" value="Загрузить прайс"/>
                <br><br>
                <input type="submit" title="Загрузить прайс" onclick="sendform()" class="btn btn-outline-info"
                       value="Загрузить прайс">
            </form>
        </div>

    </div>
    @include('template.send_form_screen')
    <div class="clear_40"></div>
    <script>
        //TODO перенести в админ js
        //Обработка кнопки очистить таблицу
        $('a[rel=truncate]').on("click", function () {
            $('.truncate-table').show();
        });
        //
        $('a[rel=cancel_truncate-table]').on("click", function () {
            $('.truncate-table').hide();
        });
        //обработка кнопки загрузить файл с прайсом
        $('a[rel=load_new_catalog]').on("click", function () {
            $(".truncate-table").hide();//скрываем открытые окна

            $(".load_catalog").animate({opacity: "show"}, 500); // показываем блок для создания нового товара
        });
        //Обработка  нажатия кнопки отмены загрузки нового прайса
        $('a[rel=cancel_load_new_catalog]').on("click", function () {

            $(".load_catalog").animate({opacity: "hide"}, 500);
        });

        function showDescr() {
            $('.desc-import').show();
            $('.open-desc').hide();
            $('.close-desc').show();
        }

        function hideDesc() {
            $('.desc-import').hide();
            $('.open-desc').show();
            $('.close-desc').hide();
        }

        //TODO перенести в главный JS перенес в global.js
        //function sendform() {
        //    $('.popup-upload_catalog').show();
        //}

    </script>
@endsection
