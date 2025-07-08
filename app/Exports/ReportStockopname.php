<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Exports\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportStockopname implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $TSO = collect();
        $user = Auth::user();

        if ($this->request->filled('location')) {
            $tStockopname = DB::table('t_stockopname AS a')
                        ->select(
                            'a.code',
                            'd.asset_tag',
                            'b.reason_name',
                            'c.name_store_street',
                            'g.condition_name',
                            'a.description',
                            'a.create_date',
                            'a.create_by',
                            'e.approval_name',
                            'a.confirm_date',
                            'a.user_confirm',
                        )
                        ->leftJoin('m_reason AS b', 'b.reason_id', '=', 'a.reason')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location')
                        ->leftJoin('t_stockopname_detail AS d', 'd.so_code', '=', 'a.code')
                        ->leftJoin('mc_approval AS e', 'e.approval_id', '=', 'a.is_confirm')
                        ->leftJoin('miegacoa_keluhan.master_resto AS f', 'f.id', '=', 'a.location')
                        ->leftJoin('m_condition AS g', 'g.condition_id', '=', 'd.condition')
                        ->where('a.is_confirm', 3)
                        ->where('a.location', $this->request->input('location'));
                        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
                            $tStockopname->whereBetween(DB::raw('DATE(a.create_date)'), [
                                $this->request->input('start_date'),
                                $this->request->input('end_date')
                            ]);
                        }
                        $TSO = $tStockopname->get();
        }

        return collect($TSO);
    }

    public function headings(): array
    {
        return [
            'Stock Opname Code',
            'Code Asset',
            'Reason',
            'Location',
            'Condition',
            'Description',
            'Create Date',
            'Create By',
            'Confirmation',
            'Confirmation Date',
            'Confirmation User',
        ];
    }
}
