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
 * Class Deliveryes
 *
 * @property \App\Delivery $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Deliveryes extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Компании доставки';

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
            AdminColumn::link('name', 'Название компании', 'link')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('name', 'like', '%'.$search.'%')
//                        ->orWhere('created_at', 'like', '%'.$search.'%')
                    ;
                })
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('sort', $direction);
                })
            ,
//            AdminColumn::text('link', 'Ссылка на сайт'),
            AdminColumn::text('method', 'Описание доставки'),
            AdminColumn::text('sort', 'Сортировка'),
            AdminColumn::custom('Видимость', function ($instance) {
                if ($instance->status == 0) {
                    $status = '<span class="text-danger">не показывает</span>';
                } else {
                    $status = '<span class="text-success">Показывает</span>';
                }
                return $status;
            })->setOrderable(function($query, $direction) {
                $query->orderBy('status', $direction);
            })
                ->setLabel('Видимость')
                ->setHtmlAttribute('class', 'text-left'),

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
            ->setName('deliveries')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-center')
        ;

//        $display->setColumnFilters([
//            AdminColumnFilter::select()
//                ->setModelForOptions(\App\Delivery::class, 'name')
//                ->setLoadOptionsQueryPreparer(function($element, $query) {
//                    return $query;
//                })
//                ->setDisplay('name')
//                ->setColumnName('name')
//                ->setPlaceholder('All cc')
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
                AdminFormElement::text('name', 'Имя компании или способ доставки')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('link', 'Ссылка на сайт')
                    ->setHelpText('Если есть или оставить пустым.'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('method', 'Метод')
                    ->setHelpText('Метод например (оплата за доставку при получении) или (Оплата при отправлении, включается в стоимость заказа) показывается на странице.')
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::number('prise', 'Стоимость доставки')
                    ->setHelpText('Оставить 0 если не определена.')
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::number('sort', 'Сортировка')
                    ->setHelpText('на страницах и в формах.'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::select('status', 'Видимость', [1 => 'Показывать', 0 => 'Не показывать'])
                    ->setHelpText('Если не видима то на странице отображаться не будет.')
                    ->setDefaultValue(1),
            ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12')
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
