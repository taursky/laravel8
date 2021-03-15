<?php

namespace App\Http\Controllers;

use App\Model\Tickets;
use App\Ticket;
use App\TicketCategory;
use App\TicketMes;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
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

    /**
     * Страница тех. поддержка
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function start(Request $request)
    {

        $tickets = Ticket::where(['email' => \Auth::user()->email, ['status', '!=', 2]])->get();
        return view('ticket.start', [
            'tickets' => $tickets,
            'title' => 'Тех. поддержка',
        ]);
    }

    /**
     * Страница создания нового тикета
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ticketsMess(Request $request)
    {

        return view('ticket.ticket_form', [
            'title' => 'Тех. поддержка',
        ]);
    }

    /**
     * Пользователь создает новый тикет
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createMess(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required|min:4',
            'text' => 'required|min:10|max:2000',
            'cat' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $subject = $request->subject;
        $text = $request->text;

        $double_ticket = Ticket::where(['email' => Auth::user()->email, 'category' => $request->cat, 'subject' => $subject,])->count();
        if ($double_ticket == 0) {
            $id_ticket = Ticket::insertGetId([
                    'login' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'category' => $request->cat,
                    'subject' => $subject,
                    'dat' => date("Y-m-d H:i:s"),
                    'status' => 0,
                ]
            );
            $res = TicketMes::insert([//DB::table('tickets_mes')->insert([
                'id_ticket' => $id_ticket,
                'text' => $text,
                'dat' => date("Y-m-d H:i:s"),
                'stat' => 0,
                'sender' => Auth::user()->email,
            ]);
            Tickets::sendUserResSupportMail(Auth::user()->name, Auth::user()->email, $subject);
            Tickets::sendSupportMessage($subject, Auth::user()->name, $text);

            $err = '<div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                	<strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>&nbsp;Ваш вопрос получен, при первой возможности вы получите ответ.
                	</div>';
            $tickets = Ticket::where(['email' => Auth::user()->email, ['status', '!=', 2]])->get();

            return view('ticket.start', [
                'tickets' => $tickets,
                'title' => 'Тех. поддержка',
                'err' => $err,
            ]);
        } else {
            $err = '<div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                	<strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>&nbsp;Такой вопрос уже задан.
                	</div>';
            $tickets = Ticket::where(['email' => Auth::user()->email, ['status', '!=', 2]])->get();
            return view('ticket.start', [
                'tickets' => $tickets,
                'title' => 'Тех. поддержка',
                'err' => $err,
            ]);
        }
    }

    /**
     * Выводит все вопросы и ответы на странице ticket_list
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function watchMess(Request $request)
    {
        $ticket = Ticket::where(['id' => $request->id])->first();
        $ticket_mess = TicketMes::where(['id_ticket' => $ticket->id])->whereNull('id_mess_answ')->orderBy('dat', 'desc')->get();
        $err = null;
        TicketMes::where('id_ticket', $ticket->id)->update(['stat' => 1]);
        $ticket_category = TicketCategory::where('id', $ticket->category)->value('title');

        return view('ticket.ticket_list', [
            'ticket' => $ticket,
            'ticket_mess' => $ticket_mess,
            'title' => 'Тех. поддержка',
            'ticket_category' => $ticket_category,
            'err' => $err,
        ]);


    }

    /**
     * Закрывает тикет
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function closeTicket(Request $request)
    {

        $err = false;
        $close_ticket = Ticket::where('id', $request->id)->update(['status' => 2]);

        $tickets = Ticket::where(['email' => \Auth::user()->email, ['status', '!=', 2]])->get();
        if ($close_ticket) {
            $err = '<div class="alert alert-secondary">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                	<strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>&nbsp;Тикет перемещён в архив.
                	</div>';
        } else {
            $err = '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                	<strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>&nbsp;Не удалось удалить тикет.
                	</div>';
        }
        return view('ticket.start', [
            'tickets' => $tickets,
            'title' => 'Тех. поддержка',
            'err' => $err,
        ]);

    }

    /**
     * Записывает вопрос пользователя
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createTickMess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|min:10|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $err = false;
        $subject = $request->subject;
        $text = $request->text;

        $double_mess = TicketMes::where(['text' => $text, 'id_ticket' => $request->id])->count();
        if ($double_mess == 0) {
            $mess_new = TicketMes::insert([//DB::table('tickets_mes')->insert([
                'id_ticket' => $request->id,
                'text' => $text,
                'dat' => date("Y-m-d H:i:s"),
                'sender' => $request->email,
            ]);
            $ticket_new = Ticket::where('id', $request->id)->update(['status' => 0]);
            if (!$mess_new) {
                $err = '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> Не удалось отправить сообщение попробуйте ещё раз.
                </div>';
            }
        } else {
            $err = '<div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </button>
                <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong> Ваше сообщение уже отправлено.
                </div>';
        }


        Tickets::sendSupportMessage($subject, $request->login, $text);
        Tickets::sendUserSupportMail($request->login, $request->email, $subject);

        $ticket = Ticket::where(['id' => $request->id])->first();
        $ticket_mess = TicketMes::where(['id_ticket' => $ticket->id])->whereNull('id_mess_answ')->orderBy('dat', 'desc')->get();
        //TicketMes::where('id_ticket', $ticket->id)->update(['stat' => 1]);
        $ticket_category = TicketCategory::where('id', $ticket->category)->value('title');

        return view('ticket.ticket_list', [
            'ticket' => $ticket,
            'ticket_mess' => $ticket_mess,
            'title' => 'Тех. поддержка',
            'ticket_category' => $ticket_category,//$page_image,
            'err' => $err,//$images,
        ]);

    }
}