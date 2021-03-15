<form action="{{route('personal')}}" class="input" method="post">
    {{ csrf_field() }}
    <input  type="submit" class="btn btn-danger" name="mail-order" value="Вернуться в кабинет">
</form>