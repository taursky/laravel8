<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    protected $validationRules = [
        'catalog' => 'required|file|mimes:xlsx,xls',
    ];

    /**
     * Записывает файл с каталогом в storage/app/catalog
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadCatalog(Request $request)
    {
        if (!empty($request->file('catalog'))) {
            $v = Validator::make($request->all(), $this->validationRules);
            if ($v->fails()) {
                $mess['msg'] = 'Файл ' . $request->file('catalog')->getClientOriginalName() . ' не может быть загружен! Загрузить можно только .xls или .xlsx файл.';
                $mess['file-class'] = 'alert-danger';
                return redirect()->back()->with($mess);
            }

            $file = $request->file('catalog');

            $upload_folder = '';
            $filename = $file->getClientOriginalName(); // image.jpg

            Storage::disk('catalog')->putFileAs($upload_folder, $file, $filename);

            $mess['msg'] = 'Файл ' . $filename . ' загружен!';
            $mess['file-class'] = 'alert-success';
        } else {
            $mess['msg'] = 'Вы не выбрали файл для загрузки!';
            $mess['file-class'] = 'alert-success';
        }

        return redirect()->back()->with($mess);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCatalog(Request $request)
    {

        $filename = $request->filename;
        //var_dump($filename);
        $res = Storage::disk('catalog')->delete($filename);

        if ($res) {
            $mess['msg'] = 'Файл ' . $filename . ' удален!';
            $mess['file-class'] = 'alert-success';
        } else {
            $mess['msg'] = 'Файл ' . $filename . ' не удалось удалить!';
            $mess['file-class'] = 'alert-danger';
        }

        return redirect()->back()->with($mess);
    }
}
