<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportKartuStock implements FromCollection, WithHeadings, WithMapping
{
    protected $request;
    protected $register_code;

    public function __construct(Request $request, $register_code = null)
    {
        $this->request = $request;
        $this->register_code = $register_code;
    }

    public function collection()
    {
        $query = DB::table('asset_tracking')
            ->select(
                'asset_tracking.start_date',
                'asset_tracking.end_date',
                'r.reason_name',
                'asset_tracking.description',
                'ma.asset_model',
                'mr1.name_store_street as asal',
                'mr2.name_store_street as menuju',
                'mr3.name_store_street as reg_loc',
                'a.qty AS saldo'
            )
            ->leftJoin('miegacoa_keluhan.master_resto as mr1', 'asset_tracking.from_loc', '=', 'mr1.id')
            ->leftJoin('miegacoa_keluhan.master_resto as mr2', 'asset_tracking.dest_loc', '=', 'mr2.id')
            ->leftJoin('m_reason AS r', 'asset_tracking.reason', '=', 'r.reason_id')
            ->leftJoin('t_out_detail AS a', 'a.out_id', '=', 'asset_tracking.out_id')
            ->leftJoin('table_registrasi_asset AS b', 'b.register_code', '=', 'asset_tracking.register_code')
            ->leftJoin('miegacoa_keluhan.master_resto as mr3', 'mr3.id', '=', 'b.register_location')
            ->leftJoin('m_assets AS ma', 'ma.asset_id', '=', 'b.asset_name')
            ->where('asset_tracking.register_code', $this->register_code);

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('asset_tracking.start_date', [
                $this->request->input('start_date') . ' 00:00:00',
                $this->request->input('end_date') . ' 23:59:59'
            ]);
        }

        return $query->get();
    }

    public function map($row): array
    {
        return [
            Carbon::parse($row->start_date)->format('H:i, d-m-Y'),
            Carbon::parse($row->end_date)->format('H:i, d-m-Y'),
            $row->reason_name,
            $row->description,
            $row->asset_model,
            $row->reg_loc,
            (is_null($row->menuju)) ? $row->asal . ' → DISPOSAL' : $row->asal . ' → ' . $row->menuju,
            ($row->saldo == 0) ? '0' : $row->saldo,
        ];
    }


    public function headings(): array
    {
        return [
            'Start Date',
            'End Date',
            'Reason',
            'Description',
            'Asset Model',
            'Register Location',
            'Location',
            'Saldo',
        ];
    }
}
