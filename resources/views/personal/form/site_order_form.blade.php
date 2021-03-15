@if(isset($count))
    <span style="font-size: 1.5rem;font-weight: 800;" class="text-muted">Количество заказов оплаченных заказов <span style="color: red">{{$count}}</span></span>
@endif
<form action="/personal/order/2" class="input" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="type" value="2">
    <input  type="submit" class="btn btn-secondary" name="none" value="Смотреть оплаченные заказы">
</form>