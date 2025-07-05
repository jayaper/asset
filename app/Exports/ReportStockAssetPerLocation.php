<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
    
        $tanggal  = $this->request->input('date') ?? Carbon::now()->format('Y-m-d');
        $jam      = $this->request->input('time') ?? '23:59';
        $datetime = $tanggal . ' ' . $jam . ':59';
        $lokasi   = $this->request->input('location');
        $T_regist = DB::table('t_out_detail as b')
            ->join('t_out as c', 'c.out_id', '=', 'b.out_id')
            ->where('c.confirm_date', '<=', $datetime)
            ->select(
                'b.asset_tag',
                'c.dest_loc',
                'c.confirm_date',
                DB::raw('ROW_NUMBER() OVER (PARTITION BY b.asset_tag ORDER BY c.confirm_date DESC) as rn')
            );

        $final = DB::table('table_registrasi_asset as a')
            ->leftJoinSub($T_regist, 'latest_movement', function ($join) {
                $join->on('a.register_code', '=', 'latest_movement.asset_tag')
                    ->where('latest_movement.rn', '=', 1);
            })
            ->select(
                'a.register_date',
                'a.register_code',
                'b.asset_model',
                'a.serial_number',
                'c.uom_name',
                'd.name AS status_asset',
                'e.condition_name',
                'f.type_name',
                'g.cat_name',
                'h.name_store_street',
                'i.layout_name'
            )
            ->leftJoin('m_assets AS b', 'b.asset_id', '=', 'a.asset_name')
            ->leftJoin('m_uom AS c', 'c.uom_id', '=', 'a.satuan')
            ->leftJoin('m_status_asset AS d', 'd.id', '=', 'a.status_asset')
            ->leftJoin('m_condition AS e', 'e.condition_id', '=', 'a.condition')
            ->leftJoin('m_type AS f', 'f.type_id', '=', 'a.type_asset')
            ->leftJoin('m_category AS g', 'g.cat_code', '=', 'a.category_asset')
            ->leftJoin('miegacoa_keluhan.master_resto AS h', function ($join) {
                $join->on(DB::raw('COALESCE(latest_movement.dest_loc, a.register_location)'), '=', 'h.id');
            })
            ->leftJoin('m_layout AS i', 'i.layout_id', '=', 'a.layout')
            ->whereRaw('COALESCE(latest_movement.confirm_date, a.created_at) <= ?', [$datetime])
            ->whereRaw('COALESCE(latest_movement.dest_loc, a.register_location) = ?', [$lokasi])
            ->get();

        return $final;
    }

    public function map($row): array
    {
        return [
            // Format tanggal sesuai kebutuhan, contoh:
            Carbon::parse($row->register_date)->format('d-m-Y'),
            $row->register_code,
            $row->asset_model,
            $row->serial_number,
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