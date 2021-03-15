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
 * Class Counters
 *
 * @property \App\Counter $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Counters extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Счетчики и Мета тэги';

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
            AdminColumn::link('name', 'Name', 'created_at')
                ->setSearchCallback(function ($column, $query, $search) {
                    return $query
                        ->orWhere('name', 'like', '%' . $search . '%');
                })
                ->setOrderable(function ($query, $direction) {
                    $query->orderBy('id', $direction);
                })
            ,
            AdminColumn::custom('Тип счетчика', function ($instance) {
                if ($instance->type == 0)
                    return '<span class="text-danger">Счетчик</span>';
                else
                    return '<span class="text-success">Тэг (мета)</span>';
            })->setLabel('Тип счетчика')->setWidth('250px'),
            AdminColumn::custom('Место показа', function ($instance) {
                if ($instance->place == 0)
                    return '<span class="text-success">Шапка</span>';
                else
                    return '<span class="text-danger">Подвал</span>';
            })->setLabel('Место показа')
                ->setWidth('250px'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-left');

        $display->setColumnFilters([
            AdminColumnFilter::select()
                ->setModelForOptions(\App\Counter::class, 'name')
                ->setLoadOptionsQueryPreparer(function ($element, $query) {
                    return $query;
                })
                ->setDisplay('name')
                ->setColumnName('name')
                ->setPlaceholder('All names')
            ,
        ]);
        $display->getColumnFilters()->setPlacement('card.heading');

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
                AdminFormElement::select('type', 'Тип счетчика', [0 => 'Счетчик', 1 => 'Тэг (мета)']),
                AdminFormElement::select('place', 'Место показа', [0 => 'В верху страницы (между <head> и </head>)', 1 => 'В конце страницы перед </body>']),
                AdminFormElement::textarea('text', 'Код мета тэга, счетчика или другой код. (Осторожно весь код будет опубликован на страницах сайта!)'),
                AdminFormElement::html('<b class="text-danger">Что бы убрать счетчик надо его удалить.</b>')
            ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12')
        ]);

        $form->getButtons()->setButtons([
            'save' => new Save(),
            'save_and_close' => new SaveAndClose(),
            'save_and_create' => new SaveAndCreate(),
            'cancel' => (new Cancel()),
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
