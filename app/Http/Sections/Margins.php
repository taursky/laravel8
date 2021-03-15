<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Brand;
use App\Margin;
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
 * Class Margins
 *
 * @property \App\Margin $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Margins extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Наценки на детали по производителям';

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
            AdminColumn::custom('Производитель', function ($instance) {
                $type = Brand::where('id', $instance->name_id)->first();
                return $type->title;
            })->setHtmlAttribute('class', 'text-left')
                ->setLabel('Производитель'),
            AdminColumn::link('value', 'Значение')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('value', 'like', '%'.$search.'%')
                    ;
                })->setWidth('150px')
                ->setHtmlAttribute('class', 'text-left')
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('status', $direction);
                })
            ,
            AdminColumn::custom('Применяемость', function ($instance) {
                if ($instance->status == 0) {
                    $status = '<span style="color: red;">Не применяется</span>';
                } else {
                    $status = '<span style="color: green;">Применяется</span>';
                }
                return $status;
            })->setOrderable(function($query, $direction) {
                $query->orderBy('status', $direction);
            })
                ->setWidth('250px')
                ->setHtmlAttribute('class', 'text-left')
                ->setLabel('Применяемость'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(false)
            ->paginate(50)
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
                AdminFormElement::select('name_id', 'Производитель', Brand::class)
                    ->setReadonly(true)
                    ->setDisplay('title'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('value', 'Значение')
                    ->setHelpText('1.0 без наценки, 1.1 = 10% , 2 = 100%, <b style="color: red">Если поставить меньше 1, то цена будет уменьшаться 0.7 = -30%, если 0 , то и цена будет 0</b>')
                    ->setDefaultValue(1.0)
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::select('status', 'Применяемость', [1 => 'Применять', 0 => 'не Применять'])
                    ->setHelpText('<b style="color: red">Очень осторожно!</b>, при отключении наценки (<b>Все производители или На складе или наценка доллара</b>), будет <b style="color: red">ошибка на сайте!</b> Если надо убрать наценку поставте Значение 1.')
                    ->setDefaultValue(1)
                ,
                AdminFormElement::html('<span class="text-danger"><b>Отключать нельзя</b>: Все производители, На складе, Наценка доллара</span>')
            ], 'col-xs-12 col-sm-9 col-md-10 col-lg-10')->addColumn([
                AdminFormElement::text('id', 'ID')->setReadonly(true),
                AdminFormElement::html('Для информации')
            ], 'col-xs-12 col-sm-3 col-md-2 col-lg-2'),
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
        $form = AdminForm::card()->addBody([
            AdminFormElement::columns()->addColumn([
                AdminFormElement::select('name_id', 'Производитель', Brand::class)
                    ->setDisplay('title'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('value', 'Значение')
                    ->setHelpText('1.0 без наценки, 1.1 = 10% , 2 = 100%, <b style="color: red">Если поставить меньше 1, то цена будет уменьшаться 0.7 = -30%, если 0 , то и цена будет 0</b>')
                    ->setDefaultValue(1.0)
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::select('status', 'Применяемость', [1 => 'Применять', 0 => 'не Применять'])
                    ->setDefaultValue(1)
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
