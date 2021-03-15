<?php

namespace App\Http\Controllers;

use App\Model\CronModel;
use App\Imports\ReserveImport;
use Illuminate\Http\Request;
use App\Exports\ReserveExport;
use App\Model\ImpexModel;
use App\Slider;
use App\TypeTextPage;
use App\Model\PartsModel;
use App\Model\XlsModel;
use App\Prise;
use App\Product;
use App\Delivery;
use App\Sale;
use App\StockWebsite;
use App\UserCompany;
use App\ChangeArticl;
use App\Detal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Object_;
use function PHPUnit\Framework\exactly;


class MainController extends Controller
{
    private $cron;

    private $input;

    private $impexModel;

    protected $validationRules;

    private $updateDate;

    /**
     * MainController constructor.
     * @param CronModel $cron
     * @param Object_ $input
     * @param ImpexModel $impexModel
     */
    public function __construct(CronModel $cron, Object_ $input, ImpexModel $impexModel)
    {
        $this->cron = $cron;
        $this->input = $input;
        $this->validationRules = [
            'prise_xls' => 'required|file|mimes:xlsx,xls|max:50',
        ];
        $this->impexModel = $impexModel;
        $this->updateDate = time() - (86400 * 60);
//        set_time_limit(1200);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        /** @var Slider $sliders */
        $sliders = Slider::where('status', 1)->orderBy('sort', 'ASC')->get();
        $text = TypeTextPage::where(['name' => 'main', 'status' => 1])->first();
        $data = [];
        $i = 0;
        foreach ($sliders as $slider) {
            $data[$i] =  '/' . $slider->image;//public_path() .
            $i++;
        }

        return view('index', [
            'sliders' => $sliders,
            'text' => $text,
            'images' => $data
        ]);
    }

    /**
     * Страница на складе
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function catalog()
    {
        $catalog = Product::orderBy('id', 'ASC')->paginate(20);
        $brands = Product::where('brand', '!=', null)->groupBy('brand')->pluck('brand');
        $nas = PartsModel::getMarginStorage();

        return view('catalog', [
            'catalog' => $catalog,
            'brands' => $brands,
            'nas' => $nas,
        ]);
    }

    public function cart()
    {

        return view('cart', [

        ]);
    }

    public function contact()
    {
        $text_pages = TypeTextPage::where(['name' => 'contact', 'status' => 1])->first();

        return view('contact', [
            'text_pages' => $text_pages,
        ]);
    }

    /**
     * Страница выбора способа доставки
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delivery()
    {
        $text_pages = TypeTextPage::where(['name' => 'delivery', 'status' => 1])->first();
        $delivery = Delivery::where('status', 1)->orderBy('status', 'asc')->get();

        return view('delivery', [
            'text_pages' => $text_pages,
            'delivery' => $delivery,
        ]);
    }

    /**
     * Страница распродажа
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sale()
    {
        $sales = Sale::where('status', 1)->get();

        return view('sale', [
            'sales' => $sales,
        ]);
    }

    /**
     * Записывает в сессию выбор доставки
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function choiceDelivery(Request $request)
    {
        $request->session()->put('delivery', $request->delivery);

        return redirect()->route('delivery');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function reservePrice()
    {

        return view('reserve.prise', [
//            'impextkey' => config('app.impex_key'),//'ToregoJvFBTnAPN4_Zb-',
        ]);
    }

    /**
     * Формирует страницу со списком деталей введенных вручную
     * для формирования прайса Excel
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reserveTable(Request $request)
    {
        $input = $this->input;
        $j = count($request->catnum) + 1;
        for ($i = 1; $i < $j; $i++) {
            // замены
            $article = strval(preg_replace('/-+/', '', trim($request->catnum[$i])));
            $number_of_replacements = ChangeArticl::where(['stat' => 1, 'article_nom' => $article])->count();
            if ($number_of_replacements > 0) {
                $article = strval(PartsModel::changeArticle($article));
            }
            $count = Detal::where('articul', $article)->count();

            if ($count > 0) {
                $input->catnum[$i] = $article;
                $input->count[$i] = intval($request->count[$i]);
                $input->status[$i] = '<span style="color:yellowgreen">Есть в каталоге</span>';
            } else {
//                ImpexModel::getImpexCatalogItem($article);
                $this->impexModel->getImpexCatalogItem($article);
                //TODO не показывает что есть в каталоге ИСПРАВИТЬ!
                $detal = Detal::where('articul', $article)->count();
                if ($detal > 0) {
                    $input->status[$i] = '<span style="color:yellowgreen">Есть в каталоге</span>';
                } else {
                    $input->status[$i] = '<span style="color:darkred">Нет в каталоге</span>';
                }
                $input->catnum[$i] = $article;
                $input->count[$i] = intval($request->count[$i]);
            }
        }

        return view('reserve.table', [
            'input' => $input,
        ]);
    }

    /**
     * Загружает файл Ексель с артиклем детали и количеством,
     * для формирования таблицы и отправкой для создания файла Excel
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function uploadPrice(Request $request)
    {
        //Обработать файл с прайсами !!!!!

        if (!empty($request->file('prise_xls'))) {
            $v = Validator::make($request->all(), $this->validationRules);
            if ($v->fails()) {
                $mess['msg'] = 'Файл ' . $request->file('prise_xls')->getClientOriginalName() . ' не может быть загружен! Загрузить можно только .xls или .xlsx файл.';
                $mess['file-class'] = 'alert-danger';

                return redirect()->back()->with($mess)->withErrors($v);
            }
            $file = $request->file('prise_xls');
            //$filename = $file->getClientOriginalName(); // prise.xls
            $lists = Excel::toArray(new ReserveImport, $file);
            $i = 1;
            foreach ($lists as $list) {
                foreach ($list as $item) {
                    // замены
                    $nom = strval(preg_replace('/-+/', '', trim($item[0])));
                    $number_of_replacements = ChangeArticl::where(['stat' => 1, 'article_nom' => $nom])->count();
                    if ($number_of_replacements > 0) {
                        $article = strval(PartsModel::changeArticle($nom));
                    } else {
                        $article = $nom;
                    }
                    $count = Detal::where(['articul' => $article])->count();
                    if ($count > 0) {

                        //TODO проверить дату обновления
                        $needToUpdate = Detal::where(['articul' => $article, ['app_date', '<', $this->updateDate]])->count();
                        if ($needToUpdate > 0) {
                            $this->impexModel->getImpexCatalogItem($article);
                        }
                        //TODO end date update

                        PartsModel::putSearchInBase($article);
                        $status = '<span style="color:yellowgreen">Есть в каталоге</span>';
                    } else {
                        $this->impexModel->getImpexCatalogItem($article);
                        //TODO не показывает что есть в каталоге ИСПРАВИТЬ!
                        $detal = Detal::where('articul', $article)->count();
                        if ($detal > 0) {
                            $status = '<span style="color:yellowgreen">Есть в каталоге</span>';
                        } else {
                            $status = '<span style="color:darkred">Нет в каталоге</span>';
                        }
                    }

                    $this->input->catnum[$i] = $article;
                    $this->input->count[$i] = intval($item[1]);
                    $this->input->status[$i] = $status;
                    $i++;
                }
            }

            return view('reserve.table', [
                'input' => $this->input,
            ]);

        } else {
            $mess['msg'] = 'Вы не выбрали файл для загрузки!';
            $mess['file-class'] = 'alert-danger';

            return redirect()->back()->with($mess);
        }
    }

    //Формируем таблицу с запчастями с выводом для последующего скачивания xls файла
    public function createList(Request $request)
    {

        $is_company = null;
        if (Auth::check()) {
            $is_company = UserCompany::where('idu', Auth::user()->id)->first();
        }

        $arr_xls = XlsModel::seachToXls($request->catnum, $request->count);

        return view('reserve.create_list', [
            'is_company' => $is_company,
            'arr_xls' => $arr_xls,
        ]);
    }

    /**
     * Создает Excel файл, удаляет старые данные и файлы
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createXlsFile(Request $request)
    {

        $directory = '';
        $files = Storage::disk('price')->allFiles($directory);
        foreach ($files as $file) {
            $time = Storage::disk('price')->lastModified($file);
            if (time() - $time > 3600) {
                Storage::disk('price')->delete($file);
            }
        }
        if (isset($request->detal_array)) {
            $detal = json_decode($request->detal_array);
            $p_id = mt_rand(100, 10000);
            foreach ($detal as $det) {
                $prise = new Prise;
                $prise->price_id = $p_id;
                $prise->nom = intval($det->nom);
                $prise->cod_zapch = $det->cod_zapch;
                $prise->produser = $det->produser;
                $prise->name = $det->name;
                $prise->weight = intval(round($det->weight));
                $prise->count = intval($det->count);
                $prise->sum_dost = intval(round($det->sum_dost));
                $prise->price = intval(round($det->price));
                $prise->all_sum = intval(round($det->all_sum));
                $prise->save();

            }
            $price_id = $p_id;

            $filename = 'price_' . date('d-m_H-i-s') . '.xlsx';
            $xls = (new ReserveExport($price_id))->store($filename, 'price');
            if ($xls) {
                Prise::where('price_id', $p_id)->delete();
            }
        }
        //TODO Добавить исключение
        return view('reserve.show_result', [
            'request' => $request->all(),
            'filename' => $filename,
        ]);
    }

    /**
     * Отдает файл с прайсом на скачивание
     * @param Request $request
     * @return mixed
     */
    public function downloadPrice(Request $request)
    {
        return Storage::disk('price')->download($request->filename);
    }

    /**
     * Страница заказа запчастей для автомобилей
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function avto()
    {
        $text_avto = TypeTextPage::where(['name' => 'avto', 'status' => 1])->first();

        return view('asks.avto', [
            'text_avto' => $text_avto,
        ]);
    }

    /**
     * Страница заказа запчастей для спецтехники
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function machines()
    {
        $text_avto = TypeTextPage::where(['name' => 'machines', 'status' => 1])->first();

        return view('asks.machines', [
            'text_avto' => $text_avto,
        ]);
    }

    /**
     * Страница заказа запчастей для мотоциклов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function moto()
    {
        $text_avto = TypeTextPage::where(['name' => 'moto', 'status' => 1])->first();

        return view('asks.moto', [
            'text_avto' => $text_avto,
        ]);
    }

    /**
     * Страница заказа запчастей для катеров
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function boat()
    {
        $text_avto = TypeTextPage::where(['name' => 'boat', 'status' => 1])->first();

        return view('asks.boat', [
            'text_avto' => $text_avto,
        ]);
    }

    /**
     * Страница правила
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rules()
    {
        $text = TypeTextPage::where(['name' => 'rules', 'status' => 1])->first();

        return view('rules', [
            'text' => $text,
        ]);
    }
}
