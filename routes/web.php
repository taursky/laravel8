<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminfunctionsController;
use App\Http\Controllers\UploadController;
use App\Model\PartsModel;
use App\Model\EmailModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('catalog', [MainController::class, 'catalog'])->name('catalog');
Route::get('cart', [MainController::class, 'cart'])->name('cart');
Route::get('reserve/price', [MainController::class, 'reservePrice'])->name('reserve.price');
Route::get('sale', [MainController::class, 'sale'])->name('sale');
Route::get('rules', [MainController::class, 'rules'])->name('rules');
Route::get('blocked', function () {
    return view('errors.blocked');
})->name('errors.blocked');

//Delivery
Route::get('delivery', [MainController::class, 'delivery'])->name('delivery');
Route::post('delivery/choice', [MainController::class, 'choiceDelivery'])->name('delivery.choice');

//Route::any('ticket', [MainController::class, 'index']);
Route::get('contact', [MainController::class, 'contact'])->name('contact');
Route::any('search/oem', [SearchController::class, 'searchOem'])->name('search.oem');//Обработка поиска по OEM

//asks
Route::get('asks/avto', [MainController::class, 'avto'])->name('asks.avto');
Route::get('asks/machines', [MainController::class, 'machines'])->name('asks.machines');
Route::get('asks/moto', [MainController::class, 'moto'])->name('asks.moto');
Route::get('asks/boat', [MainController::class, 'boat'])->name('asks.boat');

//reserve
Route::post('make/price', [MainController::class, 'reserveTable'])->name('make.price');
Route::post('upload/price', [MainController::class, 'uploadPrice'])->name('upload.price');
Route::post('create/list', [MainController::class, 'createList'])->name('create.list');
Route::post('create/xls', [MainController::class, 'createXlsFile'])->name('create.xls.file');//Создает xls файл
Route::post('download/price', [MainController::class, 'downloadPrice'])->name('download.price');//Отдает файл на скачивание

//search
Route::any('search/oem', [SearchController::class, 'searchOem'])->name('search.oem');//Обработка поиска по OEM
Route::post('search/oem/seek', [SearchController::class, 'searchOemRedirect'])->name('search.oem.redirect');//Ожидание загрузки деталей из IMPEX
Route::any('search/storage', [SearchController::class, 'searchStorage'])->name('search.storage');

//Корзина
Route::get('cart', [CartController::class, 'cart'])->name('cart');
Route::post('put/cart', [CartController::class, 'putCart'])->name('put.cart');
Route::post('refresh/cart', [CartController::class, 'refreshCart'])->name('refresh.cart');
Route::post('delete/cart', [CartController::class, 'deleteCart'])->name('delete.cart');
Route::any('order/email', [CartController::class, 'orderEmail'])->name('order.email');
Route::post('order/email/send', [CartController::class, 'orderEmailSend'])->name('order.email.send');
Route::post('order/own/send', [CartController::class, 'orderOwnSend'])->name('order.own.send');
Route::post('order/own', [CartController::class, 'orderOwn'])->name('order.own');

//Ticket
Route::any('ticket', [TicketController::class, 'start'])->name('ticket');
Route::any('make/message', [TicketController::class, 'ticketsMess'])->name('make.message');
Route::post('create/support', [TicketController::class, 'createMess'])->name('create_supp');
Route::post('support/message', [TicketController::class, 'watchMess'])->name('support.message');
Route::post('ticket/close', [TicketController::class, 'closeTicket'])->name('ticket_close');
Route::post('create/tick_mess', [TicketController::class, 'createTickMess'])->name('create_tic');

//personal
Route::any('personal', [PersonalController::class, 'home'])->name('personal');
Route::get('personal/balance', [PersonalController::class, 'personalBalance'])->name('personal.balance');
Route::get('personal/order', [PersonalController::class, 'personalOrder'])->name('personal.order');
Route::any('personal/order/{type}', [PersonalController::class, 'personalOrderSite'])->name('personal.order.site');
Route::post('create/legalEntity', [PersonalController::class, 'createLegalEntity'])->name('create.legal_entiti');//Создание Юр. лица
Route::post('update/user/data', [PersonalController::class, 'updateUserData'])->name('update.user.data');//обновление персональной информации
Route::post('update/legalEntity', [PersonalController::class, 'updateLegalEntity'])->name('update.legal_entiti');//Обновление данных Юр. лица
Route::post('personal/mail/item', [PersonalController::class, 'mailItem'])->name('mail.item');
Route::post('personal/balance/fillUp', [PersonalController::class, 'balanceFillup'])->name('personal.balance.fillup');
Route::post('delete/order', [PersonalController::class, 'deleteOrder'])->name('delete.order');
Route::post('change/pass', [PersonalController::class, 'changePass'])->name('change.pass');

//Запросы в IMPEX и запись в базу!!! Добавляем строку в таблицу для запроса прайса
Route::post('put/articul', function (Request $request) {
    return PartsModel::putArticul($request);
})->name('put.articul');
//Обновить деталь если есть в базе `detal`
Route::post('ajax/update_detal', function (Request $request) {
    return PartsModel::updateDetal($request);
})->name('ajax.update_detal');
//Записываем в базу `detal` деталь из EMEX если нет
Route::post('ajax/create_detal', function (Request $request) {
    return PartsModel::createDetal($request);
})->name('ajax.create_detal');

//MAIL send
Route::post('send/contact/mail', function (Request $request) {
    $v = Validator::make($request->all(), [
        'email' => 'required|email|max:128',
        'name' => 'required',
        'tel' => 'required',
        'message' => 'required|min:5|max:400',
        'g-recaptcha-response' => 'required|captcha',
    ]);
    if ($v->fails()) {
        return redirect()->back()->withErrors($v->errors())->withInput();
    }
    if ($request->get('name') == 'Henrypaw') {
        $mess = 'Your message is not valid';

        return redirect()->back()->with(['mess' => $mess]);
    }
    $mess = EmailModel::sendContactMail($request);

    return redirect()->back()->with(['mess' => $mess]);
})->name('send.contact.mail');

Route::post('send/spare_parts/mail', function (Request $request) {
    $v = Validator::make($request->all(), [
        'email' => 'required|email|max:128',
        'name' => 'required|min:3|max:29',
        'tel' => 'required|min:7|max:20',
        'producer' => 'required|min:3|max:40',
        'model' => 'required|min:3|max:40',
        'serialnumber' => 'required|min:5|max:20',
        'engine' => 'required|min:3|max:20',
        'catnum' => 'required|min:5|max:20',
        'message' => 'required|min:5|max:400',
        'g-recaptcha-response' => 'required|captcha',
    ]);
    if ($v->fails()) {
        return redirect()->back()->withErrors($v->errors())->withInput();
    }
    $mess = EmailModel::sendSparePartsMail($request);

    return redirect()->back()->with(['mess' => $mess]);
})->name('send.spare_parts.mail');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
// кто куда admin ?????
Route::post('truncate/table', [CartController::class, 'truncateTable'])->name('truncate.table');
Route::post('delete/provider', [CartController::class, 'deleteProvider'])->name('delete.provider');
Route::post('upload/catalog', [CartController::class, 'uploadCatalog'])->name('upload.catalog');
//Upload admin catalog
Route::post('delete/catalog', [UploadController::class, 'deleteCatalog'])->name('delete.catalog');
Route::post('upload/new/catalog', [UploadController::class, 'uploadCatalog'])->name('upload.new.catalog');


Route::get('/admin/mail_orders/{id}/edit', [AdminfunctionsController::class, 'adminOrderShow'])->name('admin.order.show');
Route::get('/admin/orders/{id}/edit', [AdminfunctionsController::class, 'adminOrderShow'])->name('admin.order.show');
Route::get('/admin/order_owns/{id}/edit', [AdminfunctionsController::class, 'adminOrderShow'])->name('admin.order.show');

Route::get('access/denied', function () {
    return view('errors.access_denied');
})->name('access.denied');

require __DIR__ . '/auth.php';
