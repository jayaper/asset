<?php

namespace App\Exports;

use App\Models\Master\MasterRegistrasiModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Exports\QrCode;
use Illuminate\Support\Facades\DB;

class ReportDisposalData implements FromCollection, WithHeadings
{
    public function collection()
    {
        $DataDisposal = DB::table('t_out')
        ->select(
            't_out.out_id',
            't_out_detail.out_id',
            'table_registrasi_asset.register_code',
            'm_assets.asset_model',
            'miegacoa_keluhan.master_resto.name_store_street',
            'table_registrasi_asset.register_date AS registrasi_date',
            't_out.create_date AS date_destruction',
            't_out.out_desc',
            'mc_approval.approval_name',
            'm_condition.condition_name'
        )
        
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        ->join('miegacoa_keluhan.master_resto', 'miegacoa_keluhan.master_resto.id', '=', 't_out.from_loc')
        ->join('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
        ->join('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')
        ->join('m_condition', 't_out_detail.condition', '=', 'm_condition.condition_id')
        ->where('t_out.out_id', 'like', 'DA%')
        ->where('is_confirm', 3)
        ->get();
    
        $dataWithIndex = $DataDisposal->values()->map(function ($item, $key) {
            return array_merge(['No' => $key + 1], (array) $item);
        });

        return collect($dataWithIndex);
    }

    public function headings(): array
    {
        return [
            'No',
            'Code Disposal Asset',
            'Asset Tag',
            'Asset Name',
            'Disposal Location',
            'Date Register',
            'Date Disposal',
            'Reason',
            'Approval Status',
            'Condition'
        ];
    }
}
