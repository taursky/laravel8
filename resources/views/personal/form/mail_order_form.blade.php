@if(isset($count))
    <span class="text-muted" style="font-size: 1.5rem;font-weight: 800;">Количество заказов на Email <span style="color: red">{{$count}}</span></span>
@endif
<form action="/personal/order/1" class="input" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="type" value="1">
    <input  type="submit" class="btn btn-success" name="mail-order" value="Смотреть почтовые заказы">
</form>