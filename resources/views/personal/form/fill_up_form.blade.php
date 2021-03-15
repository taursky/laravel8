@php
    $login_ord = Auth::user()->name;
    $email_ord = Auth::user()->email;
    $phone_ord = Auth::user()->phone;
    $user_name_ord = Auth::user()->fname;
    $last_name_ord = Auth::user()->lname;
    $user_address_ord = Auth::user()->address;

    //#генерация id заказа
    $letters = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    $count = strlen($letters);

    $intval = time();
    //#var_dump($intval);
    $result = '';
    for($i = 0;$i < 5;$i++) {
        $last = $intval%$count;
        $intval = ($intval-$last)/$count;
        $result .= $letters[$last];
    }

    $kon = rand(10,999);
    $WMI_PAYMENT_NO = $intval.'-'.$result.'-'.$kon;


@endphp
    <h4>номер операции : <strong>{{$WMI_PAYMENT_NO}}</strong></h4>
<!--<link rel="stylesheet" href="/template/style_contactform.css" type="text/css" />-->
    <form method="POST" class="form-horizontal" action="https://wl.walletone.com/checkout/checkout/Index">
        <input type="hidden" name="WMI_MERCHANT_ID" value="199667004298"/>
        <p>
            <label for="WMI_PAYMENT_AMOUNT"><span class="red-star">*</span> Сумма :</label>
            <input name="WMI_PAYMENT_AMOUNT" class="form-control" placeholder="Сумма пополнения" value="1000" pattern="\d+(\.\d{2})?" />
        </p>
        <input type="hidden" name="WMI_PAYMENT_NO"    value="{{$WMI_PAYMENT_NO}}"/>
        <input type="hidden" name="WMI_CURRENCY_ID"    value="643"/>
        <p>
            <label for="WMI_DESCRIPTION">Описание :</label>
            <input type="text" name="WMI_DESCRIPTION" class="form-control" size="30" disabled value="Пополнение баланса Барс-авто"/>
        </p>
        <input type="hidden" name="WMI_SUCCESS_URL" value="http://bars-avto.com/success"/>
        <input type="hidden" name="WMI_FAIL_URL" value="http://bars-avto.com/success"/>
        <input type="hidden" name="WMI_RECIPIENT_LOGIN" value="{{$email_ord}}"/>
        <input type="hidden" name="CUSTOMER_FIRSTNAME" value="{{$user_name_ord}}"/>
        <input type="hidden" name="CUSTOMER_LASTNAME" value="{{$last_name_ord}}"/>
        <input type="hidden" name="CUSTOMER_ADDRESS" value="{{$user_address_ord}}"/>
        <input type="hidden" name="PAYMENT_PURPOSE" value="popolnenie"/>


        <p>
            <label for="CUSTOMER_PHONE">Телефон :</label>
            <input type="text" name="CUSTOMER_PHONE" class="form-control" placeholder="Ваш телефон"  value="{{$phone_ord}}"/>
        </p>
        <p>
            <label for="CUSTOMER_EMAIL"><span class="red-star">*</span> E-mail :</label>
            <input type="text" name="CUSTOMER_EMAIL" class="form-control" placeholder="Ваш email" value="{{$email_ord}}"/>
        </p>
        <p>Нажимая на кнопку ниже, Вы соглашаетесь с <a href="{{route('rules')}}" target="_blank">правилами и условиями</a> интернет магазина <b style="color:red;">"БАРС"</b>. </p>
        <p>
            <input type="submit" class="btn btn-secondary" value="оплатить" />
        </p>
    </form>