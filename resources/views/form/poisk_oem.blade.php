<form method="post" action="{{route('search.oem.redirect')}}" id="form-oem">
    {{ csrf_field() }}
    <input type="text" name="oem_zapch" id="cod_zapch" size="80"
           title="для поиска по сайту введите артикул или наименование детали."
           placeholder=" &nbsp;&nbsp;&nbsp;&nbsp;Искать деталь по артикулу или наименованию детали" >
    <br>
    <button type="submit" class="search-button">Найти</button>
</form>

<script>
    // $(document).ready(function() {
    //     $('.search-button').on('click', function () {
    //         $('.popup-upload_catalog').css('display', 'block');
    //         $('#footer').css('display', 'none');
    //         $('.container').css('display', 'none');
    //         $('.search_oem_part').text('Подождите идет поиск запчасти.')
    //     });
    // });
</script>
