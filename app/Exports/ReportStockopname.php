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
                            'a.asset_tag',
                            'b.reason_name',
                            'c.name_store_street',
                            'd.condition_name',
                            'a.description',
                            'a.create_date',
                            'a.create_by',
                            'e.approval_name',
                            'a.confirm_date',
                            'a.user_confirm',
                        )
                        ->leftJoin('m_reason AS b', 'b.reason_id', '=', 'a.reason')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location')
                        ->leftJoin('m_condition AS d', 'd.condition_id', '=', 'a.condition')
                        ->leftJoin('mc_approval AS e', 'e.approval_id', '=', 'a.is_confirm')
                        ->leftJoin('miegacoa_keluhan.master_resto AS f', 'f.id', '=', 'a.location');
                        if($user->hasRole('SM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.id', $user->location_now);
                            });
                        }else if($user->hasRole('AM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.kode_city', $user->location_now);
                            });
                        }else if($user->hasRole('RM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.id_regional', $user->location_now);
                            });
                        }
                        if($this->request->filled('date')){
                            $tStockopname->where(function($query) {
                                $query->whereDate('a.create_date', $this->request->input('date'));
                            });
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
