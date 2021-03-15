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
 * Class ChangeArticls
 *
 * @property \App\ChangeArticl $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class ChangeArticls extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Подмена артикула';

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
            AdminColumn::text('change_nom', 'Номер на который меняем')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::link('article_nom', 'Номер который меняем')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('name', 'like', '%'.$search.'%')
                        ->orWhere('created_at', 'like', '%'.$search.'%')
                    ;
                })->setHtmlAttribute('class', 'text-center')
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('created_at', $direction);
                })
            ,
            AdminColumn::boolean('stat', 'Меняем '),
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

        $display->setColumnFilters([
            AdminColumnFilter::select()
                ->setModelForOptions(\App\ChangeArticl::class, 'name')
                ->setLoadOptionsQueryPreparer(function($element, $query) {
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
                AdminFormElement::text('article_nom', 'Номер старого артикула')
                    ->required()
                ,
                AdminFormElement::text('change_nom', 'Номер нового артикула')
                    ->required()
                ,
                AdminFormElement::select('stat', 'Подмена номера', [0 => 'Не применяется', 1 => 'Номер подменяется']),
                AdminFormElement::html('<hr>'),
//                AdminFormElement::datetime('created_at')
//                    ->setVisible(true)
//                    ->setReadonly(false)
//                ,
                AdminFormElement::html('Для замены устаревших артикулов')
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4 text-muted')->addColumn([
                AdminFormElement::text('id', 'ID')->setReadonly(true),
                AdminFormElement::html('Для информации')
            ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8 text-muted'),
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
