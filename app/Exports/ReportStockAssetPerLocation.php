<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportStockAssetPerLocation implements FromCollection, WithHeadings
{
     protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return DB::table('table_registrasi_asset AS a')
            ->select(
                'a.register_date',
                'a.register_code',
                'b.asset_model',
                'a.serial_number',
                'a.qty',
                'c.uom_name',
                'd.name AS status_asset',
                'e.condition_name',
                'f.type_name',
                'g.cat_name',
                'h.name_store_street AS lokasi_sekarang',
                'i.layout_name',
            )
            ->leftJoin('m_assets AS b', 'b.asset_id', '=', 'a.asset_name')
            ->leftJoin('m_uom AS c', 'c.uom_id', '=', 'a.satuan')
            ->leftJoin('m_status_asset AS d', 'd.id', '=', 'a.status_asset')
            ->leftJoin('m_condition AS e', 'e.condition_id', '=', 'a.condition')
            ->leftJoin('m_type AS f', 'f.type_id', '=', 'a.type_asset')
            ->leftJoin('m_category AS g', 'g.cat_code', '=', 'a.category_asset')
            ->leftJoin('miegacoa_keluhan.master_resto AS h', 'h.id', '=', 'a.location_now')
            ->leftJoin('m_layout AS i', 'i.layout_id', '=', 'a.layout')
            ->where('a.register_date', $this->request->input('date'))
            ->where('a.location_now', $this->request->input('location'))
            ->get();
    }

    public function map($row): array
    {
        return [
            // Format tanggal sesuai kebutuhan, contoh:
            Carbon::parse($row->register_date)->format('d-m-Y'),
            $row->register_code,
            $row->asset_model,
            $row->serial_number,
            ($row->qty == 1) ? 1 : "0",
            $row->uom_name,
            $row->status_asset,
            $row->condition_name,
            $row->type_name,
            $row->cat_name,
            $row->lokasi_sekarang,
            $row->layout_name,
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Asset Code',
            'Asset Name',
            'Serial Number',
            'Quantity',
            'Satuan',
            'Status Asset',
            'Condition',
            'Type Asset',
            'Category Asset',
            'Location Now',
            'Layout'
        ];
    }
}