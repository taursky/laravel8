<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\User;
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
 * Class UserCompanyes
 *
 * @property \App\UserCompany $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class UserCompanyes extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Компании';

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
            AdminColumn::custom('Пользователь', function ($instance) {
                $user = User::where('id', $instance->idu)->first();

                return $user->name;
            })->setLabel('Пользователь'),

            AdminColumn::link('name', 'Название', 'inn')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('name', 'like', '%'.$search.'%')
                    ;
                })
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('id', $direction);
                })
            ,
            AdminColumn::text('bank', 'Банк'),
            AdminColumn::text('rs', 'Р/Сч'),
            AdminColumn::text('discount', 'Скидка %'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
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

                AdminFormElement::select('idu', 'Пользователь', User::class)
                    ->setReadonly(true)
                    ->setDisplay('name'),
                AdminFormElement::text('name', 'Название Компании')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('inn', 'ИНН компании')->required(),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('ogrn', 'ОГРН компании (не обязательно)'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('fio_dir', 'ФИО Директора компании (не обязательно)'),
                AdminFormElement::number('discount', 'Оптовая скидка')
                    ->setHelpText('Оптовая скидка в  % (используется для расчетов запчастей из Японии), если 0 то скидки нет.'),
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->addColumn([
                AdminFormElement::text('bank', 'Название Банка (не обязательно)'),
                AdminFormElement::text('bik', 'БИК (не обязательно)'),
                AdminFormElement::text('rs', 'Расчетный счет (не обязательно)'),
                AdminFormElement::text('ks', 'Кор. Счет (не обязательно)'),
                AdminFormElement::textarea('adress', 'Адрес компании (не обязательно)'),
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

                AdminFormElement::select('idu', 'Пользователь', User::class)
                    ->setReadonly(false)
                    ->setDisplay('name'),
                AdminFormElement::text('name', 'Название Компании')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('inn', 'ИНН компании')->required(),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('ogrn', 'ОГРН компании (не обязательно)'),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('fio_dir', 'ФИО Директора компании (не обязательно)'),
                AdminFormElement::number('discount', 'Оптовая скидка')
                    ->setHelpText('Оптовая скидка в  % (используется для расчетов запчастей из Японии), если 0 то скидки нет.'),
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->addColumn([
                AdminFormElement::text('bank', 'Название Банка (не обязательно)'),
                AdminFormElement::text('bik', 'БИК (не обязательно)'),
                AdminFormElement::text('rs', 'Расчетный счет (не обязательно)'),
                AdminFormElement::text('ks', 'Кор. Счет (не обязательно)'),
                AdminFormElement::textarea('adress', 'Адрес компании (не обязательно)'),
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
