<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Delivery;
use App\Detal;
use App\Imports\CatalogImport;
use App\Model\EmailModel;
use App\Model\PartsModel;
use App\Order;
use App\Product;
use App\Sale;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Importer;
use Illuminate\Support\Facades\Validator;
use function MongoDB\BSON\toJSON;

class CartController extends Controller
{
    private $excel;

    /**
     * @var array|string[]
     */
    protected $validationEmail = [];

    /**
     * CartController constructor.
     * @param Excel $excel
     */
    public function __construct(Excel $excel)
    {

        $this->middleware('auth');
        $this->excel = $excel;
        $this->validationEmail = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
            'delivery' => 'required',
        ];
    }

    /**
     * Выводит страницу корзина
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cart()
    {
        $dollar = PartsModel::getDollar();
        $oem_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 1])->get();
        $product_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 2])->get();
        $sale_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 3])->get();
        $nas = PartsModel::getMarginStorage();

        return view('cart', [
            'oem_cart' => $oem_cart,
            'product_cart' => $product_cart,
            'sale_cart' => $sale_cart,
            'dollar' => $dollar,
            'nas' => $nas,
        ]);
    }

    /**
     * Записывает товар в корзину
     * @param Request $request
     * @return int
     */
    public function putCart(Request $request)
    {
        //логика внесения товара в корзину
        $type = $request->type;
        if ($type == 1) {
            $detal = Detal::where(['id' => $request->id])->first();
            $count = Cart::where(['type' => $type, 'detal_id' => $detal->id])->count();
            if ($count > 0) {
                $cart = Cart::where(['type' => $type, 'detal_id' => $detal->id])->first();
                $cart->count = $cart->count + $request->count;
                $cart->save();
            } else {
                $cart = new Cart();
                $cart->detal_id = $detal->id;
                $cart->user_id = Auth::user()->id;
                $cart->count = $request->count;
                $cart->type = 1;
                $cart->datus = time();
                $cart->save();
            }
        } elseif ($type == 2) {
            $detal = Product::where(['id' => $request->id])->first();
            $count = Cart::where(['type' => $type, 'detal_id' => $detal->id])->count();
            if ($count > 0) {
                $cart = Cart::where(['type' => $type, 'detal_id' => $detal->id])->first();
                $cart->count = $cart->count + 1;
                $cart->save();
            } else {
                $cart = new Cart();
                $cart->detal_id = $detal->id;
                $cart->user_id = Auth::user()->id;
                $cart->count = 1;
                $cart->type = 2;
                $cart->datus = time();
                $cart->save();
            }
        } elseif ($type == 3) {
            $detal = Sale::where(['id' => $request->id])->first();
            $count = Cart::where(['type' => $type, 'detal_id' => $detal->id])->count();
            if ($count > 0) {
                $cart = Cart::where(['type' => $type, 'detal_id' => $detal->id])->first();
                $cart->count = $cart->count + 1;
                $cart->save();
            } else {
                $cart = new Cart();
                $cart->detal_id = $detal->id;
                $cart->user_id = Auth::user()->id;
                $cart->count = 1;
                $cart->type = 3;
                $cart->datus = time();
                $cart->save();
            }
        } else {

            return 1;
        }

        return 1;
    }

    /**
     * Меняем количество товаров в корзине
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshCart(Request $request)
    {

        $cart = Cart::where('id', $request->id)->first();
        $cart->count = $request->count;
        $cart->save();

        return redirect()->route('cart');
    }

    /**
     * Удаляет товар из корзины
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCart(Request $request)
    {
        Cart::where('id', $request->id)->delete();

        return redirect()->route('cart');
    }

    /**
     * Страница подтверждения заказа на Email !!!!!!!!!!!!!!!! и всех остальных зависит от type
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderEmail(Request $request)
    {

        $i = 1;
        $sum = 0;
        $oem_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 1])->get();
        $sale_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 3])->get();
        $catalog_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 2])->get();
        $delivery = Delivery::where('status', 1)->orderBy('status', 'asc')->get();

        return view('order.mail', [
            'oem_cart' => $oem_cart,
            'sale_cart' => $sale_cart,
            'catalog_cart' => $catalog_cart,
            'delivery' => $delivery,
            'i' => $i,
            'sum' => $sum,
            'type' => 1,
            'link' => 'order.email.send',
        ]);
    }

    /**
     * Страница подтверждения заказа со своего счета
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderOwn(Request $request)
    {
        $i = 1;
        $sum = 0;
        $oem_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 1])->get();
        $sale_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 3])->get();
        $catalog_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 2])->get();
        $full_sum = 000;
        $f_sums = Cart::where(['user_id' => Auth::user()->id])->get();
        $nas = floatval(PartsModel::getMarginStorage());
        foreach ($f_sums as $f_sum) {
            if ($f_sum->type == 1) {
                $full_sum += PartsModel::getPriceOemDetal($f_sum->detal_id) * $f_sum->count;
            }
            if ($f_sum->type == 2) {
                $full_sum += floatval(Product::where('id', $f_sum->detal_id)->value('prise')) * $nas * $f_sum->count;
            }
            if ($f_sum->type == 3) {
                $full_sum += floatval(Sale::where('id', $f_sum->detal_id)->value('price')) * $f_sum->count;
            }
        }
        $error = false;
        if ($full_sum > Auth::user()->account) {
            $error = '<div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <strong><i class="fa fa-info"></i></strong> Сумма заказа превышает баланс счета.
                    </div>';
        }
        // var_dump(Auth::user()->account,$full_sum);
        $delivery = Delivery::where('status', 1)->orderBy('status', 'asc')->get();

        return view('order.own', [
            'oem_cart' => $oem_cart,
            'sale_cart' => $sale_cart,
            'catalog_cart' => $catalog_cart,
            'delivery' => $delivery,
            'i' => $i,
            'sum' => $sum,
            'type' => 3,
            'link' => 'order.own.send',
            'error' => $error,
        ]);
    }

    /**
     * Страница результата обработки заказа на Email !!!!!!!!!!!!!!!! и всех остальных
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderEmailSend(Request $request)
    {
        $v = Validator::make($request->all(), $this->validationEmail);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $sum = 0;
        $nas = PartsModel::getMarginStorage();
        $sale_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 3])->get();
        if (count($sale_cart) > 0) {
            foreach ($sale_cart as $sale) {
                $detal = Sale::where('id', $sale->detal_id)->first();
                $data[] = [
                    'title' => $detal->title,
                    'articul' => $detal->articul,
                    'price' => PartsModel::finalPrice($detal->price),
                    'count' => $sale->count,
                    'provider' => 'распродажа',
                    'type' => $sale->type,
                    'detal_id' => $detal->id,
                    'brand' => $detal->brand,
                ];
                $sum += $detal->price * $sale->count;
            }
        }
        $oem_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 1])->get();
        if (count($oem_cart) > 0) {
            foreach ($oem_cart as $oem) {
                $detal = PartsModel::getDetalOptions($oem->detal_id);
                $data[] = [
                    'title' => $detal['name'],
                    'articul' => $detal['articul'],
                    'price' => PartsModel::finalPrice($detal['prise']),
                    'count' => $oem->count,
                    'provider' => 'из японии',
                    'type' => $oem->type,
                    'detal_id' => $oem->detal_id,
                    'brand' => $detal['brand'],
                ];
                $sum += $detal['prise'] * $oem->count;
            }
        }
        $catalog_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 2])->get();
        if (count($catalog_cart) > 0) {
            foreach ($catalog_cart as $catalog) {
                $detal = Product::where('id', $catalog->detal_id)->first();
                $prise = PartsModel::finalPrice($detal->prise * $nas);
                $data[] = [
                    'title' => $detal->name,
                    'articul' => $detal->articul,
                    'price' => $prise,
                    'count' => $catalog->count,
                    'provider' => $detal->provider,
                    'type' => $catalog->type,
                    'detal_id' => $catalog->id,
                    'brand' => $catalog->brand,
                ];
                $sum += $prise * $catalog->count;
            }
        }
        $delivery = Delivery::where('status', 1)->orderBy('status', 'asc')->get();

        $order_nom = PartsModel::generateOrder();
        $mess = false;
        $order = false;
        if (isset($data)) {
            $order = new Order();
            $order->nom = $order_nom;
            $order->user_id = Auth::user()->id;
            $order->name = $request->name;
            $order->email = $request->email;
            $order->delivery_id = $request->delivery;
            $order->description = $request->description;
            $order->data = $data;
            $order->summa = $sum;
            $order->datus = time();
            $order->type = $request->type;
            $order->pay = 0;
            $order->status = 0;
            $order->save();
            if ($order) {
                $id = $order->id;
                // отправить email об отправке заявки
                EmailModel::sendOrderMail($id);
                EmailModel::sendOrderMailUser($id);
                Cart::where('user_id', Auth::user()->id)->delete();
                $mess = '<div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                    <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>
                        Ваша заявка <strong>№ ' . $order_nom . '</strong> принята, сообщение отправлено на E-mail: ' . $request->email . '
                  </div>';
            } else {
                $mess = '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                    <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>
                        Не удалось отправить Вашу заявку, попробуйте ещё раз
                  </div>';
            }
        }

        return view('order.mail_send', [
            'oem_cart' => $oem_cart,
            'sale_cart' => $sale_cart,
            'catalog_cart' => $catalog_cart,
            'delivery' => $delivery,
            'sum' => $sum,
            'mess' => $mess,
            'order' => $order,
        ]);

    }

    //Страница результата обработки заказа со своего счета
    public function orderOwnSend(Request $request)
    {
        $v = Validator::make($request->all(), $this->validationEmail);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }
        $sum = 0;
        $sale_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 3])->get();
        if (count($sale_cart) > 0) {
            foreach ($sale_cart as $sale) {
                $detal = Sale::where('id', $sale->detal_id)->first();
                $data[] = [
                    'title' => $detal->title,
                    'articul' => $detal->articul,
                    'price' => $detal->price,
                    'count' => $sale->count,
                    'provider' => 'распродажа',
                    'type' => $sale->type,
                    'detal_id' => $detal->id,
                ];
                $sum += $detal->price * $sale->count;
            }
        }
        $oem_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 1])->get();
        if (count($oem_cart) > 0) {
            foreach ($oem_cart as $oem) {
                $detal = PartsModel::getDetalOptions($oem->detal_id);
                $data[] = [
                    'title' => $detal['name'],
                    'articul' => $detal['articul'],
                    'price' => $detal['prise'],
                    'count' => $oem->count,
                    'provider' => 'из японии',
                    'type' => $oem->type,
                    'detal_id' => $oem->detal_id,
                ];
                $sum += $detal['prise'] * $oem->count;
            }
        }
        $catalog_cart = Cart::where(['user_id' => Auth::user()->id, 'type' => 2])->get();
        if (count($catalog_cart) > 0) {
            foreach ($catalog_cart as $catalog) {
                $detal = \App\Product::where('id', $catalog->detal_id)->first();
                $prise = $detal->prise * 1.1;
                $data[] = [
                    'title' => $detal->name,
                    'articul' => $detal->articul,
                    'price' => $prise,
                    'count' => $catalog->count,
                    'provider' => $detal->provider,
                    'type' => $catalog->type,
                    'detal_id' => $catalog->id,
                ];
                $sum += $prise * $catalog->count;
            }
        }
        $delivery = Delivery::where('status', 1)->orderBy('status', 'asc')->get();
        $order_nom = PartsModel::generateOrder();
        $mess = false;
        $order = false;
        if (isset($data)) {
            $order = new Order();
            $order->nom = $order_nom;
            $order->user_id = Auth::user()->id;
            $order->name = $request->name;
            $order->email = $request->email;
            $order->delivery_id = $request->delivery;
            $order->description = $request->description;
            $order->data = $data;
            $order->summa = floatval($sum);
            $order->datus = time();
            $order->type = $request->type;
            $order->pay = 0;
            $order->status = 0;
            $order->save();
            if ($order) {
                Cart::where('user_id', Auth::user()->id)->delete();
                $new_user = User::where('id', Auth::user()->id)->first();
                $new_user->account = $new_user->account - floatval($sum);
                $new_user->save();

                $mess = '<div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                    <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>
                        Ваша заявка <strong>№ ' . $order_nom . '</strong> принята, сообщение отправлено на E-mail: ' . $request->email . '
                  </div>';
            } else {
                $mess = '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                    <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>
                        Не удалось отправить Вашу заявку, попробуйте ещё раз
                  </div>';
            }
        }

        return view('order.own_send', [
            'oem_cart' => $oem_cart,
            'sale_cart' => $sale_cart,
            'catalog_cart' => $catalog_cart,
            'delivery' => $delivery,
            'sum' => $sum,
            'mess' => $mess,
            'order' => $order,
        ]);

    }

    // сделать через orderEmail
    public function orderKosh(Request $request)
    {
        return view('order.kosh', [
            'request' => $request,
        ]);
    }

    // сделать через orderEmail
    public function orderYandex(Request $request)
    {
        return view('order.yandex', [
            'request' => $request,
        ]);
    }

    // сделать через orderEmail
    public function orderSber(Request $request)
    {
        return view('order.sber', [
            'request' => $request,
        ]);
    }

    /**
     * Очистка таблицы Products
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function truncateTable(Request $request)
    {
        Product::truncate();

        return redirect()->back();
    }

    /**
     * Удаляет товар определенного поставщика, убирает из корзины сохраненные товары поставщика
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProvider(Request $request)
    {
        $res = Product::where('provider', $request->provider)->get();
        foreach ($res as $r) {
            $data[] = $r->id;
        }
        Cart::where('type', 2)->whereIn('detal_id', $data)->delete();
        $result = Product::where('provider', $request->provider)->delete();
        if ($result) {
            $mess['msg'] = 'Товары поставщика ' . $request->provider . ' удалены!';
            $mess['file-class'] = 'alert-success';
        } else {
            $mess['msg'] = 'Товары поставщика ' . $request->provider . ' не удалось удалить!';
            $mess['file-class'] = 'alert-danger';
        }

        return redirect()->back()->with($mess);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadCatalog(Request $request)
    {
        //загрузка данных в базу
        $filename = $request->filename;
        $exists = Storage::disk('catalog')->exists($filename);
        $path = explode('/', $filename);
        $name = end($path);
        $shortName = explode('_', $filename);
        $shortName = $shortName[0];
        if ($exists && $shortName != 'Elkin') {
            Excel::import(new CatalogImport(), $filename, 'catalog');
            $mess['msg'] = 'Товары из файла ' . $name . ' загружены!';
            $mess['file-class'] = 'alert-success';
        } else {
            $mess['msg'] = 'Файл ' . $name . ' не найден!';
            $mess['file-class'] = 'alert-danger';
        }

        return redirect()->back()->with($mess);
    }
}
