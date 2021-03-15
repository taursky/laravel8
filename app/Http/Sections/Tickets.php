<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\TicketCategory;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;

use SleepingOwl\Admin\Form\Buttons\Delete;

/**
 * Class Tickets
 *
 * @property \App\Ticket $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Tickets extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Тикеты';

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
//        $this->addToNavigation()->setPriority(100)->setIcon('fa fa-lightbulb-o');
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {

        $display = AdminDisplay::tabbed();
        //UPDATE `tickets` SET `id`=[value-1],
        //`login`=[value-2],
        //`email`=[value-3],
        //`category`=[value-4],
        //`subject`=[value-5],
        //`dat`=[value-6],
        //`answers`=[value-7],
        //`status`=[value-8],
        //`adm_answ`=[value-9] WHERE 1

        $display->setTabs(function() {
            $tabs = [];
            $columns = [
                AdminColumn::text('login', 'Логин пользователя'),
                AdminColumn::datetime('dat', 'Дата создания'),
                AdminColumn::text('email', 'Email'),
                AdminColumn::text('subject', 'Тема тикета'),
                AdminColumn::custom('Категория вопроса', function ($instance) {
                    $category = TicketCategory::where('id', $instance->category)->first();
                    return $category->title;
                })->setOrderable(function($query, $direction) {
                    $query->orderBy('status', $direction);
                })
                    ->setWidth('150px'),
                AdminColumn::custom('Статус тикета', function ($instance) {
                    if ($instance->status == 0) {
                        $status = '<span style="color: red">без ответа</span>';
                        return $status;
                    }elseif ($instance->status == 1)
                        $status = '<span style="color: green">с ответом</span>';
                    else {
                        $status = '<span style="color: black;font-weight: bold">закрыт</span>';
                    }
                    return $status;
                })->setOrderable(function($query, $direction) {
                    $direction = 'desc';
                    $query->orderBy('dat', $direction);
                })
                    ->setWidth('100px'),
            ];

            $main = AdminDisplay::table()->paginate(30, 'ticket');//->setDisplaySearch(true);
            $main->setApply(function ($query) {
                $query->where('status', 0)->orderBy('dat', 'desc');
            });
            $main->setColumns($columns);
            //кнопка посмотреть тикет
            $ticket_show_button = new \SleepingOwl\Admin\Display\ControlButton(function(\Illuminate\Database\Eloquent\Model $model) {
                return route('admin.ticket.show', 'id='.$model->getKey());
            }, 'Смотреть тикет', 50);
            $ticket_show_button->setMethod('post')
                ->hideText()
                ->setIcon('fa fa-eye');
            $main->getColumns()->getControlColumn()->addButton($ticket_show_button)->setWidth('200px');

            $ticket_ban_button = new \SleepingOwl\Admin\Display\ControlButton(function(\Illuminate\Database\Eloquent\Model $model) {
                return route('admin.ticket.ban', 'id='.$model->getKey());
            }, 'Заблокировать тикет', 50);
            $ticket_ban_button->setMethod('post')
                ->hideText()
                ->setIcon('fa fa-ban')
                ->setHtmlAttribute('class', 'btn-danger');;
            $main->getColumns()->getControlColumn()->addButton($ticket_ban_button)->setWidth('200px');
            $tabs[] = AdminDisplay::tab($main, 'Без ответа')->setActive();

            //с ответом
            $answ = AdminDisplay::table()->paginate(30, 'ticket_answ');
            $answ->setApply(function ($query) {
                $query->where('status', 1)->orderBy('dat', 'desc');
            });
            $answ->setColumns($columns);
            $answ->getColumns()->getControlColumn()->addButton($ticket_ban_button)->setWidth('200px');
            $answ->getColumns()->getControlColumn()->addButton($ticket_show_button);
            $tabs[] = AdminDisplay::tab($answ, 'С ответом');//->setActive();

            //заблокированные
            $block = AdminDisplay::table()->paginate(30, 'ticket_block');
            $block->setApply(function ($query) {
                $query->where('status', 2)->orderBy('dat', 'desc');
            });
            $block->setColumns($columns);

            $ticket_block_button = new \SleepingOwl\Admin\Display\ControlButton(function(\Illuminate\Database\Eloquent\Model $model) {
                return route('admin.ticket.unban', 'id='.$model->getKey());
            }, 'Разблокировать тикет', 50);

            $ticket_block_button->setMethod('post')
                ->hideText()
                ->setIcon('fa fa-unlock')
                ->setHtmlAttribute('class', 'btn-danger');;

            $block->getColumns()->getControlColumn()->addButton($ticket_block_button)->setWidth('100px');

            $tabs[] = AdminDisplay::tab($block, 'Заблокированные');//->setActive();

            return $tabs;

        });

        return $display;


//        $columns = [
//            AdminColumn::text('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
//            AdminColumn::link('login', 'Логин пользователя', 'dat')
//                ->setSearchCallback(function ($column, $query, $search) {
//                    return $query
//                        ->orWhere('name', 'like', '%' . $search . '%')
//                        ->orWhere('created_at', 'like', '%' . $search . '%');
//                })
//                ->setOrderable(function ($query, $direction) {
//                    $query->orderBy('created_at', $direction);
//                })
//            ,
//            AdminColumn::text('email', 'Email'),
//            AdminColumn::text('subject', 'Тема тикета'),
//            AdminColumn::custom('Категория вопроса', function ($instance) {
//                $category = TicketCategory::where('id', $instance->category)->first();
//                return $category->title;
//            })->setOrderable(function ($query, $direction) {
//                $query->orderBy('status', $direction);
//            })
//                ->setWidth('150px'),
//            AdminColumn::custom('Статус тикета', function ($instance) {
//                if ($instance->status == 0) {
//                    $status = '<span style="color: red">без ответа</span>';
//                    return $status;
//                } elseif ($instance->status == 1)
//                    $status = '<span style="color: green">с ответом</span>';
//                else {
//                    $status = '<span style="color: black;font-weight: bold">закрыт</span>';
//                }
//                return $status;
//            })->setOrderable(function ($query, $direction) {
//                $direction = 'desc';
//                $query->orderBy('dat', $direction);
//            })
//                ->setWidth('100px'),
//        ];
//
//        $display = AdminDisplay::datatables()
//            ->setName('ticket')
//            ->setOrder([[0, 'asc']])
//            ->setDisplaySearch(true)
//            ->paginate(25)
//            ->setColumns($columns)
//            ->setHtmlAttribute('class', 'table-primary table-hover th-left');
//
//        return $display;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
        $form = AdminForm::form()->setElements([
            AdminFormElement::text('login', 'Логин пользователя')->required(),
            AdminFormElement::text('email', 'Email'),
            AdminFormElement::select('category')//, 'Выберите категорию продукта', \App\TypeProdaction::where('depth','2')->get())->setDisplay('title'),
            ->setLabel('Категория вопроса')
                ->setModelForOptions(\App\TicketCategory::class)
                //->setLoadOptionsQueryPreparer(function($element, $query) {
                //    return $query
                //        ->where('depth', '2');
                //})
                ->setHtmlAttribute('placeholder', 'Выберите категорию тикета')
                ->setDisplay('title')
                ->required(),
            AdminFormElement::text('subject', 'Тема тикета'),
            AdminFormElement::select('status', 'статус',[0 =>'без ответа', 1 => 'с ответом', 2 => 'закрыт'])->setDefaultValue(1),
        ]);

        $form->getButtons()->setButtons([
            'save' => (new SaveAndClose())->setText('Сохранить')->setGroupElements([
                'cancel' => (new Cancel())->setText('Отмена'),
                'delete' => (new Delete())->setText('Удалить'),
            ]),
        ]);

        return $form;

//        $form = AdminForm::card()->addBody([
//            AdminFormElement::columns()->addColumn([
//                AdminFormElement::text('name', 'Name')
//                    ->required()
//                ,
//                AdminFormElement::html('<hr>'),
//                AdminFormElement::datetime('created_at')
//                    ->setVisible(true)
//                    ->setReadonly(false)
//                ,
//                AdminFormElement::html('last AdminFormElement without comma')
//            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4')->addColumn([
//                AdminFormElement::text('id', 'ID')->setReadonly(true),
//                AdminFormElement::html('last AdminFormElement without comma')
//            ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8'),
//        ]);
//
//        $form->getButtons()->setButtons([
//            'save' => new Save(),
//            'save_and_close' => new SaveAndClose(),
//            'save_and_create' => new SaveAndCreate(),
//            'cancel' => (new Cancel()),
//        ]);
//
//        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate($payload = [])
    {
        return $this->onEdit(null, $payload);
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return true;
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
