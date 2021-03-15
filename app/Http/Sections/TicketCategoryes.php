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
 * Class TicketCategoryes
 *
 * @property \App\TicketCategory $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class TicketCategoryes extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Категории вопросов в техподдержку';

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
            AdminColumn::link('title', 'Категория')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('title', 'like', '%'.$search.'%')
                    ;
                })
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('sort', $direction);
                })
            ,
            AdminColumn::text('sort', 'Сортировка'),
            AdminColumn::custom('Видимость', function ($instance) {
                if ($instance->status == 0) {
                    $status = '<span style="color: darkred;font-weight: 600">Не доступно</span>';
                    return $status;
                } else {
                    $status = '<span style="color: darkgreen;font-weight: 600">Доступно</span>';
                }
                return $status;
            })
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('status', $direction);
                })
                ->setlabel('Доступность')
                ->setWidth('300px'),
//            AdminColumn::boolean('name', 'On'),
//            AdminColumn::text('created_at', 'Created / updated', 'updated_at')
//                ->setWidth('160px')
//                ->setOrderable(function($query, $direction) {
//                    $query->orderBy('updated_at', $direction);
//                })
//                ->setSearchable(false)
//            ,
        ];

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-center')
        ;

//        $display->setColumnFilters([
//            AdminColumnFilter::select()
//                ->setModelForOptions(\App\TicketCategory::class, 'name')
//                ->setLoadOptionsQueryPreparer(function($element, $query) {
//                    return $query;
//                })
//                ->setDisplay('name')
//                ->setColumnName('name')
//                ->setPlaceholder('All names')
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
        $form = AdminForm::card()->addBody([
            AdminFormElement::columns()->addColumn([
                AdminFormElement::text('title', 'Категория')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::number('sort', 'Сортировка')->setDefaultValue(0),
                AdminFormElement::html('<hr>'),
                AdminFormElement::select('status', 'Доступность',[0 =>'Не доступно', 1 => 'Доступно'])->setDefaultValue(1),
//                AdminFormElement::datetime('created_at')
//                    ->setVisible(true)
//                    ->setReadonly(false)
//                ,
//                AdminFormElement::html('last AdminFormElement without comma')
            ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12')
//                ->addColumn([
//                AdminFormElement::text('id', 'ID')->setReadonly(true),
//                AdminFormElement::html('last AdminFormElement without comma')
//            ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8'),
        ]);

        $form->getButtons()->setButtons([
            'save'  => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create'  => new SaveAndCreate(),
            'cancel'  => (new Cancel()),
        ]);

        return $form;
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
