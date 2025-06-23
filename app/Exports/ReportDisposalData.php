<?php

namespace App\Exports;

use App\Models\Master\MasterRegistrasiModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Exports\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportDisposalData implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
         $final = collect();

        if ($this->request->filled('location')) {
            $T_regist = DB::table('t_out_detail AS a')
                ->select(
                    'a.asset_tag',
                    'c.asset_model',
                    'd.cat_name',
                    'b.serial_number',
                    'b.register_date',
                    'e.out_date',
                    'e.out_desc',
                    'f.reason_name',
                    'e.confirm_date',
                    'e.appr_3_user',
                )
                ->leftJoin('table_registrasi_asset AS b', 'b.register_code', '=', 'a.asset_tag')
                ->leftJoin('m_assets AS c', 'c.asset_id', '=', 'b.asset_name')
                ->leftJoin('m_category AS d', 'd.cat_code', '=', 'b.category_asset')
                ->leftJoin('t_out AS e', 'e.out_id', '=', 'a.out_id')
                ->leftJoin('m_reason AS f', 'f.reason_id', '=', 'e.reason_id')
                ->where('e.out_id', 'like', 'DA%')
                ->where('e.is_confirm', 3)
                ->where('b.location_now', $this->request->input('location'));

                if($this->request->filled('start_date') && $this->request->filled('end_date')){
                    $T_regist->whereBetween('e.out_date', [
                        $this->request->input('start_date') . ' 00:00:00',
                        $this->request->input('end_date') . ' 23:59:59'
                    ]);
                }

            $final = $T_regist->get();
        }

        return collect($final);
    }

    public function headings(): array
    {
        return [
            'Code Asset',
            'Asset Name',
            'Category Asset',
            'Serial Number',
            'Register Date',
            'Disposal Date',
            'Reason',
            'Execution',
            'Approval Date',
            'Approval By',
        ];
    }
}
