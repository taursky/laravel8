<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminDisplayFilter;
use  App\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;

/**
 * Class TicketMeses
 *
 * @property \App\TicketMes $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class TicketMeses extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Сообщения пользователей';

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
        $display = AdminDisplay::datatablesAsync()->setDisplaySearch(true)->paginate(30);

        $display->setHtmlAttribute('class', 'table-info table-hover');

        $display->with('tickets');
        $display->setFilters(
            AdminDisplayFilter::related('id_ticket')->setModel(Ticket::class)
        );

        $display->setColumns([

            AdminColumn::link('sender', 'Отправитель<br/><small>(автор сообщения)</small>')
                ->setWidth('200px'),

            AdminColumn::datetime('dat', 'Дата сообщения<br/><small>(datetime)</small>')
                ->setWidth('150px')
                ->setHtmlAttribute('class', 'text-center')
                ->setFormat('d.m.Y'),

            $country = AdminColumn::text('tickets.category', 'Категория вопроса<br/><small>( из Ticket)</small>')
                ->setHtmlAttribute('class', 'hidden-sm hidden-xs hidden-md')
                ->append(
                    AdminColumn::filter('id_ticket')
                )
                ->setOrderable('id_ticket')
                ->setWidth('90px'),
            AdminColumn::custom('',function ($instance) {
                return mb_substr($instance->text, 0, 100 );
                //return $instance->text;
            })
                ->setLabel('Текст сообщения<br/><small>(сокращен)</small>')
                ->setWidth('250px'),
            //AdminColumn::relatedLink('author.name', 'Author'),

            //$companiesCount = AdminColumn::count('companies', 'Companies<br/><small>(count)</small>', 'country.title')
            //    ->setHtmlAttribute('class', 'text-center hidden-sm hidden-xs')
            //    ->setWidth('50px'),

            //$companies = AdminColumn::lists('companies.title', 'Companies<br/><small>(lists)</small>', 'created_at')
            //    ->setHtmlAttribute('class', 'hidden-sm hidden-xs hidden-md'),

            AdminColumn::custom('Это ответ?<br/><small>(custom)</small>', function ($instance) {
                return $instance->id_mess_answ ? '<i class="fa fa-check green"></i>' : '<i class="fa fa-minus red"></i>';
            })
                ->setLabel('Это ответ?<br/><small>(опционально)</small>')
                ->setHtmlAttribute('class', 'text-center')->setWidth('70px'),
        ]);

        //$country->getHeader()->setHtmlAttribute('class', 'hidden-sm hidden-xs hidden-md');
        //$companies->getHeader()->setHtmlAttribute('class', 'hidden-sm hidden-xs hidden-md');
        //$companiesCount->getHeader()->setHtmlAttribute('class', 'hidden-sm hidden-xs');

        return $display;

//        $columns = [
//            AdminColumn::text('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
//            AdminColumn::link('name', 'Name', 'created_at')
//                ->setSearchCallback(function($column, $query, $search){
//                    return $query
//                        ->orWhere('name', 'like', '%'.$search.'%')
//                        ->orWhere('created_at', 'like', '%'.$search.'%')
//                    ;
//                })
//                ->setOrderable(function($query, $direction) {
//                    $query->orderBy('created_at', $direction);
//                })
//            ,
//            AdminColumn::boolean('name', 'On'),
//            AdminColumn::text('created_at', 'Created / updated', 'updated_at')
//                ->setWidth('160px')
//                ->setOrderable(function($query, $direction) {
//                    $query->orderBy('updated_at', $direction);
//                })
//                ->setSearchable(false)
//            ,
//        ];
//
//        $display = AdminDisplay::datatables()
//            ->setName('firstdatatables')
//            ->setOrder([[0, 'asc']])
//            ->setDisplaySearch(true)
//            ->paginate(25)
//            ->setColumns($columns)
//            ->setHtmlAttribute('class', 'table-primary table-hover th-center')
//        ;
//
//        $display->setColumnFilters([
//            AdminColumnFilter::select()
//                ->setModelForOptions(\App\TicketMes::class, 'name')
//                ->setLoadOptionsQueryPreparer(function($element, $query) {
//                    return $query;
//                })
//                ->setDisplay('name')
//                ->setColumnName('name')
//                ->setPlaceholder('All names')
//            ,
//        ]);
//        $display->getColumnFilters()->setPlacement('card.heading');
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
//            'save'  => new Save(),
//            'save_and_close'  => new SaveAndClose(),
//            'save_and_create'  => new SaveAndCreate(),
//            'cancel'  => (new Cancel()),
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
