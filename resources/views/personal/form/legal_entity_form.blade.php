<form action = "{{route($action)}}"  method = "post">
    {{ csrf_field() }}
    <table class="table form-list">
        <tr>
            <td colspan="2" class="custom-text">
                <span class="red-star">*</span> Поля отмеченные красной звездочкой, обязательны к заполнению.
            </td>
        </tr>
        <tr>
            <td style="width: 50%;font-size: 15px"><span class="red-star" >*</span> Название компании : </td>
            <td style="width: 50%" class="{{ $errors->has('nameyur') ? ' has-error' : '' }}" >
                <span class="normal-text">
                    <input type="text" name="nameyur" class="form-control form-control-sm" id="nameyur" value="{{old('nameyur')?old('nameyur'):(isset($is_company->name)?$is_company->name:'')}}">
                </span>
                @if ($errors->has('nameyur'))
                    <span class="help-block">
                        <small><strong>{{ $errors->first('nameyur') }}</strong></small>
                    </span>
                @endif
            </td>
        </tr>
        <tr>
            <td>ОГРН (компании) : </td>
            <td class="{{ $errors->has('ogrn') ? ' has-error' : '' }}">
                <input type="text" name="ogrn" class="form-control form-control-sm" id="ogrn" value="{{old('ogrn')?old('ogrn'):(isset($is_company->ogrn)? $is_company->ogrn:'')}}">
                @if ($errors->has('ogrn'))
                    <span class="help-block">
                        <small><strong>{{ $errors->first('ogrn') }}</strong></small>
                    </span>
                @endif
            </td>
        </tr>
        <tr>
            <td><span class="red-star">*</span> ИНН (компании) : </td>
            <td class="{{ $errors->has('inn') ? ' has-error' : '' }}">
                <input type="text" name="inn" class="form-control form-control-sm" id="inn" value="{{old('inn')?old('inn'):(isset($is_company->inn)?$is_company->inn:'')}}">
                @if ($errors->has('inn'))
                    <span class="help-block">
                        <small><strong>{{ $errors->first('inn') }}</strong></small>
                    </span>
                @endif
            </td>
        </tr>
        <tr>
            <td>ФИО Руководителя : </td>
            <td><input type="text" name="fio_dir" class="form-control form-control-sm" id="fio_dir" value="{{old('fio_dir')?old('fio_dir'):(isset($is_company->fio_dir)?$is_company->fio_dir:'') }}"></td>
        </tr>
        <tr>
            <td colspan="2">Адрес компании : </td>
        </tr>
        <tr>
            <td colspan="2" class="{{ $errors->has('ur_adress') ? ' has-error' : '' }}">
                <textarea  name="ur_adress" class="form-control form-control-sm" id="ur_adress" rows="5" >{{old('ur_adress')?old('ur_adress'):(isset($is_company->adress)?$is_company->adress:'')}}</textarea>
                @if ($errors->has('ur_adress'))
                    <span class="help-block">
                        <small><strong>{{ $errors->first('ur_adress') }}</strong></small>
                    </span>
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2"><h4><b>Банковские реквизиты</b></h4></td>
        </tr>
        <tr>
            <td>Банк: </td>
            <td><input type="text" name="bank" class="form-control form-control-sm" id="bank" value="{{old('bank')?old('bank'):(isset($is_company->bank)?$is_company->bank:'')}}"></td>
        </tr>
        <tr>
            <td>БИК: </td>
            <td><input type="text" name="bik" class="form-control form-control-sm" id="bik" value="{{old('bik')?old('bik'):(isset($is_company->bik)?$is_company->bik:'')}}"></td>
        </tr>
        <tr>
            <td>Кор. счет: </td>
            <td><input  type="text" name="ks" class="form-control form-control-sm" id="ks" value="{{old('ks')?old('ks'):(isset($is_company->ks)?$is_company->ks:'')}}"></td>
        </tr>
        <tr>
            <td>Расчетный счет: </td>
            <td><input  type="text" name="rs" class="form-control form-control-sm" id="rs" value="{{old('rs')?old('rs'):(isset($is_company->rs)?$is_company->rs:'')}}"></td>
        </tr>
        <tr>
            <td colspan="2"> </td>
        </tr>
        <tr>
            <td> Ваша скидка :</td>
            <td><b>{{isset($is_company->discount)?$is_company->discount:0}} % </b></td>
        </tr>
    </table>

    <div class="clear_10"></div>
    <button class="btn btn-secondary" type="submit"  name="urData" value ="save" >Сохранить</button>
</form>