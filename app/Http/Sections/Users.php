<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Role;
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
 * Class Users
 *
 * @property \App\User $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Users extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Пользователи';

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
            AdminColumn::link('name', 'Логин', 'created_at')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                    ;
                })
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('id', $direction);
                })
            ,
            AdminColumn::text('email', 'E-mail'),
            AdminColumn::text('phone', 'Телефон'),
            AdminColumn::text('ip', 'IP'),
            AdminColumn::custom('Роль пользователя', function ($instance) {
                if ($instance->role == 3) {
                    return '<span class="text-danger">Заблокирован</span>';
                }elseif ($instance->role == 1) {
                    return '<b class="text-warning">АДМИН</b>';
                }
                else {
                    return '<span class="text-success">Активный</span>';
                }
            })
                ->setLabel('Роль пользователя'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('users')
            ->setOrder([[1, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(30)
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
                AdminFormElement::text('name', 'Логин')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('fname', 'Имя'),
                AdminFormElement::text('lname', 'Фамилия'),

                AdminFormElement::text('phone', 'Телефон'),


                AdminFormElement::number('account', 'Счет пользователя (Для оплаты на сайте)')
                    ->setHelpText('<b class="text-danger">Внимательно!</b> пополнение счета дает возможность купить товар на сайте.')
                    ->setDefaultValue(0),
                AdminFormElement::select('role', 'Роль пользователя')
                    ->setHelpText('<b class="text-danger">Внимательно!</b> роль <b style="color:red">"Admin"</b> дает доступ к админке сайта!')
                    ->setModelForOptions(Role::class)
                    ->setDisplay('val')
                    ->setDefaultValue(2)
                    ->required()
                ,
//                AdminFormElement::html('last AdminFormElement without comma')
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')
                ->addColumn([
                    AdminFormElement::text('email', 'Электронная почта')
                        ->setHelpText('Используется для авторизации на сайте')
                        //->addValidationRule('email')
                        ->setReadOnly(true)
                        ->required(),
                    AdminFormElement::password('password', 'Пароль')
                        ->hashWithBcrypt()
                        ->allowEmptyValue()
                        ->required(),
                    AdminFormElement::text('ip', 'IP'),
                    AdminFormElement::datetime('created_at', 'Дата создания')
                        ->setVisible(true)
                        ->setReadonly(true)
                    ,
                    AdminFormElement::datetime('updated_at', 'Дата изменения')
                        ->setVisible(true)
                        ->setReadonly(true)
                    ,

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
                AdminFormElement::text('name', 'Логин')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('fname', 'Имя'),
                AdminFormElement::text('lname', 'Фамилия'),

                AdminFormElement::text('phone', 'Телефон'),


                AdminFormElement::number('account', 'Счет пользователя (Для оплаты на сайте)')
                    ->setHelpText('<b class="text-danger">Внимательно!</b> пополнение счета дает возможность купить товар на сайте.')
                    ->setDefaultValue(0),
                AdminFormElement::select('role', 'Роль пользователя')
                    ->setHelpText('<b class="text-danger">Внимательно!</b> роль <b style="color:red">"Admin"</b> дает доступ к админке сайта!')
                    ->setModelForOptions(Role::class)
                    ->setDisplay('val')
                    ->setDefaultValue(2)
                    ->required()
                ,
//                AdminFormElement::html('last AdminFormElement without comma')
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')
                ->addColumn([
                    AdminFormElement::text('email', 'Электронная почта')
                        ->setHelpText('Используется для авторизации на сайте')
                        ->addValidationRule('email')
                        ->setReadOnly(false)
                        ->required(),
                    AdminFormElement::password('password', 'Пароль')
                        ->hashWithBcrypt()
                        ->allowEmptyValue()
                        ->required(),
                    AdminFormElement::text('ip', 'IP'),
//                    AdminFormElement::datetime('created_at', 'Дата создания')
//                        ->setVisible(true)
//                        ->setReadonly(true)
//                    ,
//                    AdminFormElement::datetime('updated_at', 'Дата изменения')
//                        ->setVisible(true)
//                        ->setReadonly(true)
//                    ,

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
