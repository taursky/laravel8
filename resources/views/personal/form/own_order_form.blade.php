@if(isset($count))
    <span style="font-size: 1.5rem;font-weight: 800;" class="text-muted">Количество заказов оплаченных с личного счета <span style="color: red">{{$count}}</span></span>
@endif
<form action="/personal/order/3" class="input" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="type" value="3">
    <input  type="submit" class="btn btn-warning" name="mail-order" value="Смотреть заказы оплаченые на сайте">
</form>