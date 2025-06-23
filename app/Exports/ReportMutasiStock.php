<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportMutasiStock implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = DB::table('t_out_detail AS a')
                    ->select(
                        'a.asset_tag',
                        'a.out_id',
                        'c.appr_1_user',
                        'c.appr_1_date',
                        'c.appr_2_user',
                        'c.appr_2_date',
                        'c.appr_3_user',
                        'c.appr_3_date',
                        'd.asset_model',
                        'e.uom_name',
                        'f.name_store_street AS lokasi_asal',
                        'g.name_store_street AS lokasi_akhir',
                        'h.condition_name',
                        'a.serial_number',
                        'i.reason_name',
                        'c.create_date',
                        'j.approval_name',
                        'c.confirm_date',
                        'c.out_date'
                    )
                    ->leftJoin('table_registrasi_asset AS b', 'b.register_code', '=', 'a.asset_tag')
                    ->leftJoin('t_out AS c', 'c.out_id', '=', 'a.out_id')
                    ->leftJoin('m_assets AS d', 'd.asset_id', '=', 'b.asset_name')
                    ->leftJoin('m_uom AS e', 'e.uom_id', '=', 'b.satuan')
                    ->leftJoin('miegacoa_keluhan.master_resto AS f', 'f.id', '=', 'c.from_loc')
                    ->leftJoin('miegacoa_keluhan.master_resto AS g', 'g.id', '=', 'c.dest_loc')
                    ->leftJoin('m_condition AS h', 'h.condition_id', '=', 'a.condition')
                    ->leftJoin('m_reason AS i', 'i.reason_id', '=', 'c.reason_id')
                    ->leftJoin('mc_approval AS j', 'j.approval_id', '=', 'c.is_confirm')
                    ->where('a.out_id', 'like', 'AM%');
                    if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
                        $query->whereBetween('c.out_date', [
                            $this->request->input('start_date') . ' 00:00:00',
                            $this->request->input('end_date') . ' 23:59:59'
                        ]);
                    }

        return collect($query->get());
    }

    public function headings(): array
    {
        return [
            'Asset Tag',
            'Transfer Number',
            'Approval 1 User',
            'Approval 1 Date',
            'Approval 2 User',
            'Approval 2 Date',
            'Approval 3 User',
            'Approval 3 Date',
            'Asset Name',
            'Satuan',
            'From Location',
            'Destination Location',
            'Condition',
            'Serial No.',
            'Reason Name',
            'Transfer Date',
            'Confirmation',
            'Confirmation Date',
            'Created At'
        ];
    }
}

