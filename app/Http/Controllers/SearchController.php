<?php

namespace App\Http\Controllers;

use App\Detal;
use App\Model\ImpexCurlModel;
use App\Model\ImpexModel;
use App\Model\PartsModel;
use App\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $impexModel;

    /**
     * SearchController constructor.
     */
    public function __construct(ImpexModel $impexModel)
    {
        //$this->middleware('auth');
        $this->impexModel = $impexModel;
    }

    /**
     * Страница результата поиска в базе detal
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchOem(Request $request)
    {
        $error = false;
        $articul = $request->oem_zapch;
        $array['count_oem'] = 0;
        $array['result_detals'] = false;
        //Запрос на поиск
        if (!$request->oem_zapch || trim($request->oem_zapch) == '') {
            $error = '<div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                                </button>
                                <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>
                                 Ваш запрос не содержит данных.
                            </div>';
        } else {
            //В модели ищем деталь
            $array = PartsModel::resultSearchOem($request->oem_zapch);
        }
        $count_sklad = 0;
        if ($array['result_sklad']) {
            foreach ($array['result_sklad'] as $res) {
                $count_sklad += count($res);
            }
        }
        return view('result.search_oem', [
            'count_oem' => $array['count_oem'],//$count_oem,
            'error' => $error,
            'articul' => $articul,
            'result_detals' => $array['result_detals'],//$result_detals,
            'result_sklad' => $array['result_sklad'],
            'count_sklad' => $count_sklad,
        ]);

    }


    /**
     * Промежуточная страница поиска деталей OEM
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function searchOemRedirect(Request $request)
    {
        $articul = $request->oem_zapch;

        if (!$request->oem_zapch || trim($request->oem_zapch) == '') {
            $error = '<div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px"><i class="fa fa-times" aria-hidden="true"></i></span>
                                </button>
                                <strong><i class="fa fa-info-circle" aria-hidden="true"></i></strong>
                                 Ваш запрос не содержит данных.
                            </div>';
            $array['count_oem'] = 0;
            $array['result_detals'] = false;
            $array['result_sklad'] = false;

            return view('result.search_oem', [
                'count_oem' => $array['count_oem'],
                'error' => $error,
                'articul' => $articul,
                'result_detals' => $array['result_detals'],
                'result_sklad' => $array['result_sklad'],
            ]);
        } else {
            $articuls = explode(' ', $articul);

            $updateDate = time() - (86400 * 30);
            foreach ($articuls as $partNo) {
                $partNo = trim($partNo);//удаляем пробелы
                $partNo = strip_tags($partNo);
                $partNo = preg_replace('/-+/', '', $partNo);// удаляем минусы из запроса
                /** @var Detal $realPart */
                $realPart = Detal::where(['articul' => $partNo, ['app_date', '<', $updateDate]])->first();
                if ($realPart && $realPart->app_date < $updateDate) {
                    $realPart = false;
                }
                if (!$realPart) {
//                    $result = ImpexCurlModel::getImpexCurl($partNo);
                    $this->impexModel->getImpexCatalogItem($partNo);
                }
            }

            return redirect()->route('search.oem', [
                'oem_zapch' => $articul,
            ]);
        }
    }

    /**
     * Страница результата поиска на складе
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchStorage(Request $request)
    {
        $nas = PartsModel::getMarginStorage();
        $empty = null;
        if (trim($request->part) == '' || iconv_strlen($request->part) < 3) {
            $empty = 'Запрос слишком короткий';
            $items = null;
        } else {

            $zapros = preg_replace('/-+/', '', $request->part);

            // Разобраться с брендом

            $searches = explode(" ", trim($zapros));
            $name = [];
            $articlul = [];
            foreach ($searches as $item => $value) {
                if ($request->name == 'on' || ($request->articul != 'on' && $request->name != 'on')) {
                    $name[] = ['name', 'like', '%' . $value . '%'];
                }
                if ($request->articul == 'on' || ($request->articul != 'on' && $request->name != 'on')) {
                    $articlul[] = ['articul', 'like', '%' . $value . '%'];
                }
            }
            $data = [];
            $data_0 = null;
            $articlul_0 = null;
            $i = 0;
            foreach ($name as $item => $value) {
                if ($i == 0) {
                    $data_0 = [$value];
                } else {
                    $data[] = $value;
                }
                $i++;
            }
            $i = 0;
            foreach ($articlul as $item => $value) {
                if ($i == 0) {
                    $articlul_0 = [$value];
                } else {
                    $articlul[] = $value;
                }
                $i++;
            }
            if ($request->brand == 'on') {
                $andwhere = ['brand' => $request->brand_lable];
                $orwhere_brand = ['name', 'like', '%' . $request->brand_lable . '%'];
            } else {
                $andwhere = null;
                $orwhere_brand = null;
            }
            $request_part = preg_replace('/-+/', '', $request->part);
            if ($request->strong == 'on' && $request->name != 'on' && $request->articul != 'on') {
                $items = Product::where(function ($query) use ($request_part) {
                    $query->where('name', 'like', '%' . $request_part . '%')
                        ->orWhere('articul', 'like', '%' . $request_part . '%');
                })
                    ->where(function ($query) use ($andwhere, $orwhere_brand) {
                        if ($andwhere) {
                            $query->where($andwhere)
                                ->orWhere([$orwhere_brand]);
                        }
                    })
                    ->paginate(20);
            } elseif ($request->strong == 'on' && $request->name == 'on') {
                $items = Product::where('name', 'like', '%' . $request_part . '%')
                    ->where(function ($query) use ($andwhere, $orwhere_brand) {
                        if ($andwhere) {
                            $query->where($andwhere)
                                ->orWhere([$orwhere_brand]);
                        }
                    })
                    ->paginate(20);
            } elseif ($request->strong == 'on' && $request->articul == 'on') {
                $items = Product::where('articul', 'like', '%' . $request_part . '%')
                    ->where(function ($query) use ($andwhere, $orwhere_brand) {
                        if ($andwhere) {
                            $query->where($andwhere)
                                ->orWhere([$orwhere_brand]);
                        }
                    })
                    ->paginate(20);
            } else {
                $items = Product::where(function ($query) use ($data_0, $articlul_0, $data, $articlul) {
                    if ($data_0) {
                        $query->where($data_0);
                    } elseif (!$data_0 && $articlul_0) {
                        $query->where($articlul_0);
                    }
                    $query->orWhere(function ($query) use ($data, $articlul) {
                        foreach ($data as $dat) {
                            $query->orWhere([$dat]);
                        }
                        foreach ($articlul as $art) {
                            $query->orWhere([$art]);
                        }
                    });
                })
                    ->where(function ($query) use ($andwhere, $orwhere_brand) {
                        if ($andwhere) {
                            $query->where($andwhere)
                                ->orWhere([$orwhere_brand]);
                        }
                    })
                    ->paginate(20);
            }
        }
        $brands = Product::where('brand', '!=', null)->groupBy('brand')->pluck('brand');

        return view('result.search_storage', [
            'empty' => $empty,
            'items' => $items,
            'brands' => $brands,
            'request' => $request,
            'nas' => $nas,
        ]);
    }

}
