<?php
use App\Http\Controllers\AdminfunctionsController;

//Route::get('', ['as' => 'admin.dashboard', function () {
//	$content = 'Define your dashboard here.';
//	return AdminSection::view($content, 'Dashboard');
//}]);

//Route::get('information', ['as' => 'admin.information', function () {
//	$content = 'Define your information here.';
//	return AdminSection::view($content, 'Information');
//}]);
//Migrations пользователи в новую таблицу
Route::get('/user/migrate', ['as' => 'admin.user.migrate',function (){
    $results = \App\Model\MigrateModel::usersMigrate();
    $content = 'запись';
    foreach ($results as $key=>$value ){
        $content .= $key.' = '.$value.'<br>' ;
    }

    return AdminSection::view($content, 'Запись в таблицу Users');
}]);
//BAN USER
Route::post('/users/ban', ['as' => 'admin.users.ban', function (\Illuminate\Http\Request $request) {
    \App\User::where('id', (int)$request->id)->update(['role' => 3]);
    return redirect()->back();
}]);
Route::post('/users/unban', ['as' => 'admin.users.unban', function (\Illuminate\Http\Request $request) {
    \App\User::where('id', (int)$request->id)->update(['role' => 2]);
    return redirect()->back();
}]);


Route::get('/admin/product/add', ['as' => 'admin.product.add', 'uses' => '\App\Http\Controllers\AdminfunctionsController@showProductPage']);
Route::get('/admin/mail_orders/{id}/edit', [AdminfunctionsController::class, 'adminOrderShow'])->name('admin.order.show');
Route::get('/admin/search/show', ['as' => 'admin.search.show', 'uses' => '\App\Http\Controllers\AdminfunctionsController@showSearchPage']);

//Блокировка тикета
Route::post('/ticket/ban', ['as' => 'admin.ticket.ban', function (\Illuminate\Http\Request $request) {
    \App\Ticket::where('id', (int)$request->id)->update(['status' => 2]);
    return redirect()->back();
}]);
//Разблокировка тикета
Route::post('/ticket/unban', ['as' => 'admin.ticket.unban', function (\Illuminate\Http\Request $request) {
    \App\Ticket::where('id', (int)$request->id)->update(['status' => 1]);
    return redirect()->back();
}]);
//Просмотр тикета
Route::post('/ticket/show', ['as' => 'admin.ticket.show', function (\Illuminate\Http\Request $request) {
    $show_form = \App\Model\Tickets::showTicketAdmin($request->id);
    return AdminSection::view($show_form, 'Просмотр тикета');
}]);
//Ответ на тикет
Route::get('/ticket/answ', ['as' => 'admin.ticket.answ', function (\Illuminate\Http\Request $request) {
    $show_form = \App\Model\Tickets::showFormAnswAdmin($request->id);
    return AdminSection::view($show_form, 'Ответить на вопрос');
}]);
Route::post('/ticket/create/answ', ['as' => 'admin.ticket.create.answ', function (\Illuminate\Http\Request $request) {
    $show_form = \App\Model\Tickets::createAnsw($request);
    return AdminSection::view($show_form, 'Ответ');
}]);
