const $ = require('jquery');
// global.$ = require('jquery');

$(document).ready(function () {
    $('.put-cart').on('click', function (e) {/*#put-cart button*/
        e.preventDefault();

        $form = $(this).parents('.form_add_to_cart');
        console.log($(this).parents('.form_add_to_cart'));
        if ($form.length > 0) {
            $id = $form.find('.product_id').val();
            $product_type = $form.find('.product_type').val();
            $id_user = $form.find('.id_user').val();
            $count = $('#count-to-cart' + $id).val();

            $.ajax({
                url: "/put/cart",
                type: "POST",
                data: {type: $product_type, id: $id, idu: $id_user, count: $count},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $form.find('.cart_send').css('display', 'block');
                    setTimeout(function () {
                        $form.find('.cart_send').css('display', 'none');
                    }, 500);

                    $cart_count = $('.smalcart-count-text').text();
                    $cart_count = parseFloat($cart_count) + parseFloat($count);
                    $('.smalcart-count-text').text($cart_count);
                },
                error: function (msg) {
                    console.log(msg);
                }
            });
        }
    });
});

function sendform() {
    $('.popup-upload_catalog').show();
}

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

$(document).ready(function () {
    $('.search-button').on('click', function () {
        $('.popup-upload_catalog').css('display', 'block');
        $('#footer').css('display', 'none');
        $('.container').css('display', 'none');
        $('.search_oem_part').text('Подождите идет поиск запчасти.')
    });

});
//page catalog
$('#search-group input:checkbox').click(function(){
    if ($(this).is(':checked')) {
        $('#search-group input:checkbox').not(this).prop('checked', false);
    }
});
$('#brand-check').click(function(){
    if ($(this).is(':checked')){
        $('.only-brand').show(100);
    } else {
        $('.only-brand').hide(100);
    }
});
if ($('#brand-check').is(':checked')){
    $('.only-brand').show(100);
}
// mobile menu button
$(document).ready(function () {
    var mobileMenu;
    var topLogo;
    var closeMobileMenu;
// var modal = $('.modal'); var overlay = $('.modal__overlay');
// var modalContent = $('.modal__content');

    var menuOpened = false;

    function toggleMobileMenu() {
        if (!menuOpened) {
            $(document).scrollTop(0);
            mobileMenu.slideDown(function () {
                topLogo.fadeIn();
                closeMobileMenu.fadeIn();
                $(document.body).addClass('mobile-menu-opened');
            });
        } else {
            $(document.body).removeClass('mobile-menu-opened');
            topLogo.fadeOut();
            closeMobileMenu.fadeOut();
            mobileMenu.slideUp();
        }

        menuOpened = !menuOpened;
    }

    $('#hamburger').click(function () {
        mobileMenu = mobileMenu || $('.mobile-menu');
        topLogo = topLogo || $('#top_logo');

        if (closeMobileMenu === undefined) {
            closeMobileMenu = $('#close_mobile_menu');

            closeMobileMenu.click(function () {
                toggleMobileMenu();
            });
        }

        toggleMobileMenu();
    });
});

/////////////
$(document).ready(function(){
    $("#put_more").on("click", (function(event ){
        event.preventDefault();
        var catnum = $('#num').val();
        var con = $('#cou').val();
        var loop = $('.loop').last().html();

        $.ajax({
            url: '/put/articul',
            type: 'POST',
            data: {catnum:catnum, coun:con,loop:loop},
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            //dataType: 'JSON',
            //cache: false,
            success: function (data) {
                //alert(data);
                $('#num').val('');
                $('#cou').val(1);
                $('.loop_0').html('добавить');
                $('#table_parts > tbody:last-child').append(data);
                //console.log(data);
                document.getElementById('send-to-table').style.display="block" ;
            }
        });
    }));

});
/// reserve price
$(document).ready(function() {
    $('#prise_detal').on('submit', function () {
        $('.popup-upload_catalog').show();
    });
});
