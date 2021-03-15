<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Cart;
use App\User;
use App\Detal;
use App\Product;
use App\Sale;
use App\TypeCart;
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
 * Class Carts
 *
 * @property \App\Cart $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Carts extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Корзины пользователей';

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
            AdminColumn::link('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),

            AdminColumn::custom('Пользователь', function ($instance) {
                $user = User::where('id', $instance->user_id)->first();

                return $user->name;
            })
                ->setOrderable(function ($query, $direction) {
                    $query->orderBy('user_id', $direction);
                })
                ->setLabel('Пользователь')
            ,
            AdminColumn::custom('Артикул', function ($instance) {
                $type = TypeCart::where('value', $instance->type)->first();
                switch ($type->value) {
                    case 1:
                        $product = Detal::where('id', $instance->detal_id)->value('articul');
                        break;
                    case 2:
                        $product = Product::where('id', $instance->detal_id)->value('articul');
                        break;
                    case 3:
                        $product = Sale::where('id', $instance->detal_id)->value('articul');
                        break;
                }

                return $product;
            })
                ->setOrderable(function ($query, $direction) {
                    $query->orderBy('detal_id', $direction);
                })
                ->setLabel('Артикул')
            ,
            AdminColumn::text('count', 'Количество'),
            AdminColumn::custom('Вид заказа', function ($instance) {
                $type = TypeCart::where('value', $instance->type)->value('name');
                return $type;
            })->setOrderable(function($query, $direction) {
                $query->orderBy('type', $direction);
            })
                ->setLabel('Вид заказа'),
            AdminColumn::custom('Дата заказа', function ($instance) {
                $date = date('d.m.Y', $instance->datus);
                return $date;
            })->setOrderable(function($query, $direction) {
                $query->orderBy('datus', $direction);
            })
                ->setLabel('Дата заказа'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('carts')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(false)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-left');

//        $display->setColumnFilters([
//            AdminColumnFilter::select()
//                ->setModelForOptions(Cart::class, 'name')
//                ->setLoadOptionsQueryPreparer(function($element, $query) {
//                    return $query;
//                })
//                ->setDisplay('Пользователь')
//                ->setColumnName('name')
//                ->setPlaceholder('Все пользователи')
//            ,
//        ]);
//        $display->getColumnFilters()->setPlacement('card.heading');

        return $display;
    }

//    /**
//     * @param int|null $id
//     * @param array $payload
//     *
//     * @return FormInterface
//     */
//    public function onEdit($id = null, $payload = [])
//    {
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
//    }
//
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
