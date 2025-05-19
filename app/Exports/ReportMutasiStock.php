<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ReportMutasiStock implements FromCollection, WithHeadings
{
    public function collection()
    {
        $DataMutasiStock = DB::table('t_out')
            ->select(
                't_out_detail.out_id',
                't_out.create_by',
                't_out.tf_code',
                't_out.appr_1_user',
                't_out.appr_1_date',
                't_out.appr_2_user',
                't_out.appr_2_date',
                't_out.appr_3_user',
                't_out.appr_3_date',
                'table_registrasi_asset.register_code',
                'm_assets.asset_model',
                't_out_detail.qty',
                'm_uom.uom_name',
                'from_location.name_store_street AS from_store',
                'dest_location.name_store_street AS dest_store',
                'm_reason.reason_name AS reason',
                't_out.out_date',
                'm_condition.condition_name',
                'table_registrasi_asset.serial_number',
                't_out.out_desc',
                't_out.is_confirm',
                't_out.updated_at',
                't_out.created_at'
            )
            ->leftJoin('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
            ->leftJoin('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
            ->leftJoin(DB::raw('miegacoa_keluhan.master_resto AS from_location'), 't_out.from_loc', '=', 'from_location.id')
            ->leftJoin(DB::raw('miegacoa_keluhan.master_resto AS dest_location'), 't_out.dest_loc', '=', 'dest_location.id')
            ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->leftJoin('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->leftJoin('m_uom', 't_out_detail.uom', '=', 'm_uom.uom_id')
            ->leftJoin('m_condition', 't_out_detail.condition', '=', 'm_condition.condition_id')
            ->get();

        $dataWithFormatted = $DataMutasiStock->map(function ($item, $index) {
            $item = (array) $item;

            return [
                'No' => $index + 1,
                'Movement Id' => $item['out_id'],
                'User Create' => $item['create_by'],
                'Transfer Code' => $item['tf_code'],
                'Approval 1 User' => $item['appr_1_user'],
                'Approval 1 Date' => $item['appr_1_date'],
                'Approval 2 User' => $item['appr_2_user'],
                'Approval 2 Date' => $item['appr_2_date'],
                'Approval 3 User' => $item['appr_3_user'],
                'Approval 3 Date' => $item['appr_3_date'],
                'Asset Tag' => $item['register_code'],
                'Asset Name' => $item['asset_model'],
                'Quantity' => $item['qty'],
                'Satuan' => $item['uom_name'],
                'From Location' => $item['from_store'],
                'To Location' => $item['dest_store'],
                'Reason Name' => $item['reason'],
                'Transfer Date' => $item['out_date'],
                'Condition' => $item['condition_name'],
                'Serial No.' => $item['serial_number'],
                'Reason' => $item['out_desc'],
                'Confirmation' => ($item['is_confirm'] == 3) ? 'Yes' : 'No',
                'Confirmation Date' => $item['updated_at'],
                'Created At' => $item['created_at'],
            ];
        });

        return collect($dataWithFormatted);
    }

    public function headings(): array
    {
        return [
            'No',
            'Movement Id',
            'User Create',
            'Transfer Code',
            'Approval 1 User',
            'Approval 1 Date',
            'Approval 2 User',
            'Approval 2 Date',
            'Approval 3 User',
            'Approval 3 Date',
            'Asset Tag',
            'Asset Name',
            'Quantity',
            'Satuan',
            'From Location',
            'To Location',
            'Reason Name',
            'Transfer Date',
            'Condition',
            'Serial No.',
            'Reason',
            'Confirmation',
            'Confirmation Date',
            'Created At'
        ];
    }
}
