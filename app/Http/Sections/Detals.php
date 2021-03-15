<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Brand;
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
 * Class Detals
 *
 * @property \App\Detal $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Detals extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Каталог деталей на заказ из японии';

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation()->setPriority(100)->setIcon('fa fa-lightbulb-o');
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {
        $columns = [
            AdminColumn::text('id', '#')->setWidth('90px')->setHtmlAttribute('class', 'text-center')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('brand', 'like', '%'.$search.'%')
                        ->orWhere('name_jp', 'like', '%'.$search.'%')
                        ->orWhere('name_in', 'like', '%'.$search.'%')
                        ->orWhere('name_ru', 'like', '%'.$search.'%')
                    ;
                })
            ,
            AdminColumn::link('articul', 'Артикул', 'brand'),
            AdminColumn::text('name_jp', 'Яп. имя'),
            AdminColumn::text('name_in', 'Англ. имя'),
            AdminColumn::text('name_ru', 'Русс. имя'),
            AdminColumn::text('price_jp', 'Цена йен'),
            AdminColumn::custom('is_original', function ($instance) {
                if ($instance->is_original == 0) {
                    $status = '<b class="text-danger">Не оригинал</b>';
                } else {
                    $status = '<b class="text-success">Оригинал</b>';
                }

                return $status;
            })->setOrderable(function($query, $direction) {
                $query->orderBy('is_original', $direction);
            })->setWidth('200px')
                ->setLabel('Оригинал')
                ->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('weight', 'Вес'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('details')
            ->setOrder([[0, 'desc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-center')
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
                AdminFormElement::text('brand', 'Марка')
                    ->setReadonly(true)
                    ->setHelpText('Бренд.'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('name_jp', 'Название Японское'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('name_in', 'Название Английское'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('name_ru', 'Название Русское'),
                AdminFormElement::html('<b class="text-danger">! Хотя бы одно название должно быть.</b>'),
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')
                ->addColumn([
                    AdminFormElement::text('articul', 'Артикул')
                        ->required('Надо заполнить')
                    ,
                    AdminFormElement::html('<hr>'),
                    AdminFormElement::number('price_jp', 'Цена Япония')
                        ->required('Без этого нельзя')
                        ->setHelpText('Цена в иенах.')
                    ,
                    AdminFormElement::html('<hr>'),
                    AdminFormElement::text('price', 'Цена RUB'),
                    AdminFormElement::html('<hr>'),
                    AdminFormElement::text('weight', 'Вес'),
                    AdminFormElement::hidden('app_date', '')->setDefaultValue(time()),
                    AdminFormElement::html('<hr>'),
                    AdminFormElement::select('is_original', 'Оригинал', [0 => 'Не оригинал', 1 => 'Оригинал']),
//                AdminFormElement::text('id', 'ID')->setReadonly(true),
//                AdminFormElement::html('last AdminFormElement without comma')
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6'),
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
//        return $this->onEdit(null, $payload);
        $form = AdminForm::card()->addBody([
            AdminFormElement::columns()->addColumn([
                AdminFormElement::select('brand', 'Марка', Brand::class)
                    ->setDisplay('title')
                    ->required('Без этого нельзя')
                    ->setHelpText('Бренд.'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('name_jp', 'Название Японское'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('name_in', 'Название Английское'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('name_ru', 'Название Русское'),
                AdminFormElement::html('<b class="text-danger">! Хотя бы одно название должно быть.</b>'),
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')
                ->addColumn([
                    AdminFormElement::text('articul', 'Артикул')
                        ->required('Надо заполнить')
                    ,
                    AdminFormElement::html('<hr>'),
                    AdminFormElement::number('price_jp', 'Цена Япония')
                        ->required('Без этого нельзя')
                        ->setHelpText('Цена в иенах.')
                    ,
                    AdminFormElement::html('<hr>'),
                    AdminFormElement::text('price', 'Цена RUB'),
                    AdminFormElement::html('<hr>'),
                    AdminFormElement::text('weight', 'Вес'),
                    AdminFormElement::hidden('app_date', '')->setDefaultValue(time()),
                    AdminFormElement::html('<hr>'),
                    AdminFormElement::select('is_original', 'Оригинал', [0 => 'Не оригинал', 1 => 'Оригинал']),
//                AdminFormElement::text('id', 'ID')->setReadonly(true),
//                AdminFormElement::html('last AdminFormElement without comma')
                ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6'),
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
