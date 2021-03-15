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
 * Class Sliders
 *
 * @property \App\Slider $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Sliders extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Слайдер настройка';

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
            AdminColumn::link('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-left'),
            AdminColumn::image('image', 'Изображение')->setWidth('250px')->setHtmlAttribute('class', 'text-left'),
            AdminColumn::link('sort', 'Сортировка')->setWidth('100px'),
            AdminColumn::custom('Видимость', function ($instance) {
                if ($instance->status == 0) {
                    $status = '<span class="text-danger">не показывает</span>';
                    return $status;
                } else {
                    $status = '<span class="text-success">Показывает</span>';
                }
                return $status;
            })->setOrderable(function ($query, $direction) {
                $query->orderBy('status', $direction);
            })
                ->setWidth('300px'),
        ];

        $display = AdminDisplay::datatables()
            ->setName('sliders')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(false)
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
                AdminFormElement::text('url', 'Ссылка на страницу (не обязательно)')
                    ->setHelpText('Если добавить, то при клике на изображение перенаправить на указанный URL, например если поставить &laquo;/contact&raquo;, то перейдет на страницу контакты.'),
                AdminFormElement::number('sort', 'Сортировка, чем меньше номер, тем раньше показывается')
                    ->setHelpText('Самый маленький номер покажет картинку первой при загрузке страницы главная, самый большой - последней.'),
                AdminFormElement::select('status', 'Видимость', ['1' => 'показывает', '0' => 'не показывает'])
                    ->setHelpText('Если поставить <b style="color: darkred">&laquo;не показывает&raquo;</b>, то картика не будет отображаться.')
                    ->setDefaultValue(1),
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4')
                ->addColumn([
                    AdminFormElement::image('image', 'Изображение')->setUploadPath(function (\Illuminate\Http\UploadedFile $file) {
                        return 'images/slider'; // public/files
                    })->setUploadSettings([
                        //'orientate' => [],
                        'resize' => [920, null, function ($constraint) {
                            $constraint->upsize();
                            $constraint->aspectRatio();
                        }],
                        'fit' => [920, 399, function ($constraint) {
                            $constraint->upsize();
                            $constraint->aspectRatio();
                        }]
                    ])
                        ->setHelpText('<b style="color: darkred">Внимание!</b> Если не загрузить или удалить изображение, то на главной странице в слайдере будет показываться маленькая иконка картинки.')
                        ->required('Необходимо загрузить изображения'),
                    AdminFormElement::html('Картинка которая будет в слайдере.')
                ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8'),
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
