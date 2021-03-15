<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;

/**
 * Class MailOrders
 *
 * @property \App\MailOrder $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class MailOrders extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Заказы на Email';

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
        $columns = [
            AdminColumn::text('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),

            AdminColumn::link('name', 'Name')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('name', 'like', '%'.$search.'%')
//                        ->orWhere('created_at', 'like', '%'.$search.'%')
                    ;
                })
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('created_at', $direction);
                })
            ,
            AdminColumn::text('email', 'Email')->setOrderable(false),
            AdminColumn::text('description', 'Сообщение')->setOrderable(false),
            AdminColumn::custom('Дата создания', function ($instance) {
                $date = date('d.m.Y', $instance->datus);
                return $date;
            })
                ->setOrderable(false)
                ->setLabel('Дата заказа'),
            AdminColumn::custom('Сумма заказа', function ($instance){
                return number_format($instance->summa, 2, '.', ' ');
            })
                ->setOrderable(false)
                ->setLabel('Сумма заказа'),//
            AdminColumn::custom('Статус', function ($instance) {
                if ($instance->status == 1) {
                    $status = '<span style="color:darkgreen">Доставлено</span>';
                    return $status;
                } else {
                    $status = '<span style="color:darkred">Не доставлено</span>';
                }
                return $status;
            })
                ->setOrderable(false)
                ->setLabel('Статус'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('mail_order')
            ->setApply(function ($query) {
                $query->where('type', 1)->orderBy('datus', 'desc');
            })
            ->setOrder([[0, 'desc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-left')
        ;

//        $display->setColumnFilters([
//            AdminColumnFilter::select()
//                ->setModelForOptions(\App\MailOrder::class, 'email')
//                ->setLoadOptionsQueryPreparer(function($element, $query) {
//                    return $query;
//                })
//                ->setDisplay('email')
//                ->setColumnName('email')
//                ->setPlaceholder('All emails')
//            ,
//        ]);
//        $display->getColumnFilters()->setPlacement('card.heading');

        return $display;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
//        $form = AdminForm::card()->addBody([
//            AdminFormElement::columns()->addColumn([
//                AdminFormElement::text('name', 'Имя')
//                    ->setReadonly(true)
//                    ->required()
//                ,
//                AdminFormElement::html('<hr>'),
//                AdminFormElement::text('email', 'Email')->setReadonly(true),
//                AdminFormElement::html('<hr>'),
//                AdminFormElement::text('summa', 'Сумма')->setReadonly(true),
//                AdminFormElement::html('<hr>'),
////                AdminFormElement::textarea('data', 'data')->setReadonly(true),
////                AdminFormElement::html('<hr>'),
//                AdminFormElement::textarea('description', 'Письмо')->setReadonly(true),
//                AdminFormElement::html('<hr>'),
////                AdminFormElement::datetime('created_at')
////                    ->setVisible(true)
////                    ->setReadonly(false)
////                ,
////                AdminFormElement::html('last AdminFormElement without comma')
//            ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12')
////                ->addColumn([
////                AdminFormElement::text('id', 'ID')->setReadonly(true),
////                AdminFormElement::html('last AdminFormElement without comma')
////            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6'),
//        ]);
//
//        $form->getButtons()->setButtons([
//            'save'  => new Save(),
//            'save_and_close'  => new SaveAndClose(),
//            'save_and_create'  => new SaveAndCreate(),
//            'cancel'  => (new Cancel()),
//        ]);
//
//        return $form;
    }

//    /**
//     * @return FormInterface
//     */
//    public function onCreate($payload = [])
//    {
//        return $this->onEdit(null, $payload);
//    }
//
//    /**
//     * @return bool
//     */
//    public function isDeletable(Model $model)
//    {
//        return true;
//    }
//
//    /**
//     * @return void
//     */
//    public function onRestore($id)
//    {
//        // remove if unused
//    }
}
