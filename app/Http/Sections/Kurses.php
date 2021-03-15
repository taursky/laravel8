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
 * Class Kurses
 *
 * @property \App\Kurs $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Kurses extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Курсы валют для расчета стоимости';

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
            AdminColumn::custom('Дата создания', function ($instance) {
                $date = date('d.m.Y', $instance->date);
                return $date;
            })
                ->setOrderable(function ($query, $direction) {
                    $query->orderBy('date', $direction);
                })
                ->setLabel('Дата курса'),
            AdminColumn::text('USD', 'Доллар'),
            AdminColumn::text('JPY', 'Йена'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('curs')
            ->setOrder([[0, 'desc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-left');

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
                AdminFormElement::custom()
                    ->setDisplay(function (Model $model) {
                        return 'Дата курса - <strong style="color: darkred">' . date('d.m.Y', $model->date) . '</strong>';
                    })
                    ->setReadOnly(true),
                AdminFormElement::text('USD', 'Доллар')->required(),
                AdminFormElement::text('JPY', 'Йена'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::html('В следующий рабочий день будет применяться новый курс.')
            ], 'col-xs-12 col-sm-9 col-md-10 col-lg-10')
                ->addColumn([
                    AdminFormElement::text('id', 'ID')->setReadonly(true),
                    AdminFormElement::html('<span class="text-muted">Для информации</span>')
                ], 'col-xs-12 col-sm-3 col-md-2 col-lg-2'),
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
        $form = AdminForm::card()->addBody([
            AdminFormElement::columns()->addColumn([
                AdminFormElement::date('date', 'Дата курса, в расчет берется только последняя дата')
                    ->mutateValue(function ($value) {
                        return strtotime($value);
                    })
                    ->setHelpText('<span class="text-danger">Если надо поставить курс на долго, надо поставить дату до которой нужен этот курс, при необходимости всегда можно удалить запись.</span>')
                    ->setCurrentDate(),
                AdminFormElement::text('USD', 'Доллар')->required(),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('JPY', 'Йена'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::html('В следующий рабочий день будет применяться новый курс.')
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
