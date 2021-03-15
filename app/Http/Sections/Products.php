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
 * Class Products
 *
 * @property \App\Product $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Products extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Продукция на складе';

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
            AdminColumn::text('id', '#')->setWidth('90px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::link('name', 'Название')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('name', 'like', '%'.$search.'%')
                        ->orWhere('articul', 'like', '%'.$search.'%')
                        ->orWhere('brand', 'like', '%'.$search.'%')
                    ;
                })
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('created_at', $direction);
                })
            ,
            AdminColumn::text('brand', 'Изготовитель'),
            AdminColumn::text('articul', 'Артикул'),
            AdminColumn::text('balance', 'Остаток'),
            AdminColumn::text('provider', 'Поставщик'),
            AdminColumn::text('prise', 'Цена'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('products')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-left')
        ;

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
                AdminFormElement::text('name', 'Название')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('brand', 'Изготовитель')
                    ->setVisible(true)
                    ->setReadonly(false)
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('articul', 'Артикул')
                    ->setVisible(true)
                    ->setReadonly(false)
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('balance', 'Остаток')
                    ->setVisible(true)
                    ->setReadonly(false)
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('provider', 'Поставщик')
                    ->setVisible(true)
                    ->setReadonly(false)
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('prise', 'Цена')
                    ->setVisible(true)
                    ->setReadonly(false)
                    ->required()
                ,
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
