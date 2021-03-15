<?php
/**
 *  Класс для обработки - Персональных данных пользователя
 *  PHP 7.0 +
 * @author Сергей Таюрский <taursky@yandex.ru>
 */

namespace App\Http\Controllers;

use App\BalanceHistory;
use App\Delivery;
use App\Order;
use App\UserCompany;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PersonalController extends Controller
{
    /**
     * PersonalController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Страница личный кабинет
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {

        $is_company = UserCompany::where('idu', Auth::user()->id)->first();
        return view('personal.home', [
            'is_company' => $is_company,
        ]);
    }

    /**
     * Страница баланс пользователя
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function personalBalance()
    {

        $history = BalanceHistory::where('idu', Auth::user()->id)->get();
        return view('personal.balance', [
            'history' => $history,
        ]);
    }

    /**
     * Страница с формой отправки на платежную систему
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function balanceFillup(Request $request)
    {

        return view('personal.fillup', [

        ]);
    }

    /**
     * Страница со списком заказов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function personalOrder()
    {
        //$orders = Order::where('user_id', Auth::user()->id)->get();
        $email_count = Order::where(['user_id' => Auth::user()->id, 'type' => 1])->count();
        $own_count = Order::where(['user_id' => Auth::user()->id, 'type' => 3])->count();
        $site_count = Order::where(['user_id' => Auth::user()->id, 'type' => 2])->count();
        return view('personal.order', [
            //'orders' => $orders,
            'email_count' => $email_count,
            'own_count' => $own_count,
            'site_count' => $site_count,
        ]);
    }

    /**
     * Удаляет заказ из базы данных
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleteOrder(Request $request)
    {
        Order::where('id', $request->id)->delete();
        return redirect()->route('personal.order');
    }

    /**
     * Страница с заказами
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function personalOrderSite(Request $request)
    {
        $order = Order::where(['user_id' => Auth::user()->id, 'type' => $request->type])->orderBy('datus', 'DESC')->paginate(10);
        $count_1 = Order::where(['user_id' => Auth::user()->id, 'type' => 1])->count();
        $count_2 = Order::where(['user_id' => Auth::user()->id, 'type' => 2])->count();
        $count_3 = Order::where(['user_id' => Auth::user()->id, 'type' => 3])->count();
        switch ($request->type) {
            case 1:
                $title = 'Заказы на Email';
                break;
            case 2:
                $title = 'Оплаченные заказы';
                break;
            case 3:
                $title = 'Оплаченные заказы со своего счета';
                break;
            default:
                $title = 'Заказы';
        }

        return view('personal.order_site', [
            'order' => $order,
            'title' => $title,
            'type' => $request->type,
            'count_1' => $count_1,
            'count_2' => $count_2,
            'count_3' => $count_3,

        ]);
    }

    /**
     * Страница отображает заказа подробно
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mailItem(Request $request)
    {
        $order = Order::where('id', $request->id)->first();
        $delivery = Delivery::where('id', $order->delivery_id)->first();
        return view('personal.item.mail', [
            'order' => $order,
            'delivery' => $delivery,
            'type' => $request->type,
        ]);
    }

    /**
     * Обновление персоналбной инфомации
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateUserData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'new_name' => 'required|min:3|max:30',
            'new_lname' => 'required|min:3|max:30',
            'new_birthday' => 'required|date_format:d.m.Y',
            'new_phone' => 'required|phone|min:10|max:17',
            'new_address' => 'required|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $new_user = DB::table('users')->where(['id' => Auth::user()->id])->update([
            'fname' => $request->new_name,
            'lname' => $request->new_lname,
            'phone' => $request->new_phone,
            'birthday' => $request->new_birthday,
            'address' => $request->new_address,
        ]);
        if ($new_user) {
            $mess['msg'] = 'Вы успешно изменили данные!';
            $mess['file-class'] = 'alert-success';
        } else {
            $mess['msg'] = 'Данные не удалось изменить!';
            $mess['file-class'] = 'alert-danger';
        }

        return redirect()->back()->with($mess);
    }

    /**
     * Записывает в базу новое Юр. лицо
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createLegalEntity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nameyur' => 'required|min:5',
            'inn' => 'required|numeric|inn',
            'ogrn' => 'ogrn',
            'ur_adress' => 'max:210',
            //'new_address' => 'required',
        ]);

        if ($validator->fails()) {
            $mess['is_legal'] = true;
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with($mess);
        }
        $legal = DB::table('user_company')->insert([
            'idu' => Auth::user()->id,
            'name' => $request->nameyur,
            'inn' => $request->inn,
            'ogrn' => $request->ogrn,
            'fio_dir' => $request->fio_dir,
            'bank' => $request->bank,
            'bik' => $request->bik,
            'rs' => $request->rs,
            'ks' => $request->ks,
            'adress' => $request->ur_adress,
        ]);
        if ($legal) {
            $mess['msg'] = 'Вы успешно зарегистрировались как Юридическое лицо!';
            $mess['file-class'] = 'alert-success';
        } else {
            $mess['msg'] = 'Не удалось зарегистрировать Вас как Юридическое лицо!';
            $mess['file-class'] = 'alert-danger';
        }
        return redirect()->back()->with($mess);
    }

    /**
     * Обновление информации Юр. Лица
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateLegalEntity(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nameyur' => 'required|min:5',
            'inn' => 'required|numeric|inn',
            'ogrn' => 'ogrn',
            'ur_adress' => 'max:210',
            //'new_address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        //UPDATE `user_company` SET `id`=[value-1],
        //`idu`=[value-2],
        //`inn`=[value-3],
        //`ogrn`=[value-4],
        //`fio_dir`=[value-5],
        //`bank`=[value-6],
        //`bik`=[value-7],
        //`rs`=[value-8],
        //`ks`=[value-9],
        //`adress`=[value-10],
        //`name`=[value-11],
        //`discount`=[value-12] WHERE 1
        $legal = DB::table('user_company')->where(['idu' => Auth::user()->id])
            ->update([
                'name' => $request->nameyur,
                'inn' => $request->inn,
                'ogrn' => $request->ogrn,
                'fio_dir' => $request->fio_dir,
                'bank' => $request->bank,
                'bik' => $request->bik,
                'rs' => $request->rs,
                'ks' => $request->ks,
                'adress' => $request->ur_adress,
            ]);
        if ($legal) {
            $mess['msg'] = 'Вы успешно изменили данные!';
            $mess['file-class'] = 'alert-success';
        } else {
            $mess['msg'] = 'Данные не удалось изменить!';
            $mess['file-class'] = 'alert-danger';
        }
        return redirect()->back()->with($mess);
    }

    /**
     * Замена пароля пользователем
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePass(Request $request)
    {
        if (Auth::user()->role == 3) {
            return redirect()->route('errors.blocked');
        }
        $validator = Validator::make($request->all(), [
            'pass' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            $mess['msg'] = 'Заполните обязательные поля!';
            $mess['file-class'] = 'alert-danger';
            return redirect()
                ->back()
                ->withErrors($validator)
                ->with($mess)
                ->withInput();
        }

        $pass = $request->pass;
        $newPass = $request->password;
        if (Hash::check($pass, Auth::user()->password)) {
            //  добавляем новый пароль в базу данных
            $user = User::where('id', Auth::user()->id)->first();
            $user->password = Hash::make($newPass);
            $user->save();
            $user_login = Auth::user()->name;
            $Site = "\"БАРС-авто\"";
            $email = Auth::user()->email;
            $title = 'Изменение пароля для ' . $user_login . ' на сайте ' . $Site . '!';
            $letter = "Вы изменили пароль для аккаунта \" $user_login \", на сайте $Site <br>\r\nВаш новый пароль: $newPass <br>\r\nС уважением команда сайта $Site <br>\r\n";
            $letter .= '<a href="http://bars-avto.com/login"> Войти на сайт </a>' . "\r\n";
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; Charset=UTF-8' . "\r\n";
            // mail($email, $title, $letter, $headers);

            $mess['msg'] = 'Вы изменили пароль и можете войти в личный кабинет.';
            $mess['file-class'] = 'alert-success';
        } else {
            $mess['msg'] = 'старый пароль введен не верно!';
            $mess['file-class'] = 'alert-danger';
        }
        return redirect()->back()->with($mess);

    }

}
