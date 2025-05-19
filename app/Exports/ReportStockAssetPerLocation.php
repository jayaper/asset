<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportStockAssetPerLocation implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $DataStockAssetPerLocation = DB::table('table_registrasi_asset AS a')
        ->select(
            'a.register_date',
            'a.register_code',
            'b.asset_model',
            'a.serial_number',
            'a.qty',
            'c.uom_name',
            'd.condition_name',
            'g.type_name',
            'h.cat_name',
            'e.name_store_street AS lokasi',
            'f.layout_name'
        )
        ->leftjoin('m_assets AS b', 'b.asset_id', '=', 'a.asset_name')
        ->leftjoin('m_uom AS c', 'c.uom_id', '=', 'a.satuan')
        ->leftjoin('m_condition AS d', 'd.condition_id', '=', 'a.condition')
        ->leftjoin('miegacoa_keluhan.master_resto AS e', 'e.id', '=', 'a.location_now')
        ->leftjoin('m_layout AS f', 'f.layout_id', '=', 'a.layout')
        ->leftjoin('m_type AS g', 'g.type_id', '=', 'a.type_asset')
        ->leftjoin('m_category AS h', 'h.cat_code', '=', 'a.category_asset')
        ->where('a.qty', '>', 0)
        ->get();

        $dataWithIndex = $DataStockAssetPerLocation->values()->map(function ($item, $key) {
            return array_merge(['No' => $key + 1], (array) $item);
        });

        return collect($dataWithIndex);
    }

    public function headings(): array
    {
        return [
            'No',
            'Date',
            'Asset Code',
            'Asset Name',
            'Serial Number',
            'Quantity',
            'Satuan',
            'Condition',
            'Type Asset',
            'Category Asset',
            'Location Now',
            'Layout'
        ];
    }
}
