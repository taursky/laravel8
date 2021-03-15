<?php

namespace App\Exports;


use App\Prise;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
//use Maatwebsite\Excel\Concerns\WithColumnFormatting;
//use Maatwebsite\Excel\Concerns\WithMapping;


class ReserveExport implements  FromQuery, WithHeadings, ShouldAutoSize //, WithMapping  WithColumnFormatting,
{
    use Exportable;

    /**
     * ReserveExport constructor.
     * @param $price_id
     */
    public function __construct($price_id)
    {
        $this->price_id = $price_id;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '№',
            'Код детали',
            'Производитель',
            'Наименование',
            'Вес детали',
            'Количество',
            'Сумма доставки',
            'Цена',
            'Сумма',
        ];
    }

   /* public function map($array): array
    {
        return [
            $array->nom,
            $array->cod_zapch,
            $array->produser,
            $array->name,
            $array->weight,
            $array->count,
            $array->sum_dost,
            $array->price,
            $array->all_sum,
        ];
    }*/

    /**
     * @return $this
     */
    public function query()
    {

        return Prise::query()->where('price_id', $this->price_id)->select('nom', 'cod_zapch', 'produser', 'name', 'weight', 'count', 'sum_dost', 'price', 'all_sum')->orderBy('nom', 'ASC');
    }

    /**
     * @return array
     */
   /* public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }*/
}
