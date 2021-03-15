<?php

namespace App\Http\Controllers;


use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Auth;
use DB;

class AdminfunctionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function showProductPage()
    {
        if (Auth::user()->role != 1) {
            return redirect()->route('access.denied');
        }
        $products = Product::groupBy('provider')->get(['provider']);

        return view('admin.product.add', [
            'products' => $products,
        ]);
    }

    /**
     * Страница просмотра заказа
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function adminOrderShow(Request $request)
    {
        if (Auth::user()->role != 1) {
            return redirect()->route('access.denied');
        }
        $order = Order::where('id', $request->id)->first();
        $user = User::where('id', $order->user_id)->first();
        $link = '/admin/mail_orders';
        if ($order->type == 1)
            $link = '/admin/mail_orders';
        if ($order->type == 2)
            $link = '/admin/orders';
        if ($order->type == 3)
            $link = '/admin/order_owns';

        return view('admin.order.show', [
            'order' => $order,
            'user' => $user,
            'link' => $link,
        ]);
    }

    /**
     * Показывает страницу с количеством запросов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showSearchPage()
    {
        if (Auth::user()->role != 1) {
            return redirect()->route('access.denied');
        }
        $r = request('page');
        if (!$r) $page = 0;
        else $page = 30 * ($r - 1);
        $searches = DB::table('seachers')
            ->select('articul', DB::raw('COUNT(articul) as count'))
            //->with('called')
            ->groupBy('articul')
            //->having('articul', '!=', null)
            ->orderBy('count', 'desc')//->orderBy('timus', 'desc')->get();
            ->paginate(30);

        return view('admin.search.show', [
            'searches' => $searches,
            'page' => $page,
        ]);
    }

}
