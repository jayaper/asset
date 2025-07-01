<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFormat implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        $user = Auth::user();
        $query = DB::table('table_registrasi_asset AS a')
            ->select(
                'a.register_code',
            )
            ->leftJoin('miegacoa_keluhan.master_resto AS resto', 'resto.id', '=', 'a.location_now')
            ->where('a.status_asset', 1)
            ->where('a.qty', 1);
            if($user->hasRole('SM')){
                $query->where(function($q) use ($user){
                    $q->where('resto.id', $user->location_now);
                });
            }else if($user->hasRole('AM')){
                $query->where(function($q) use ($user){
                    $q->where('resto.kode_city', $user->location_now);
                });
            }else if($user->hasRole('RM')){
                $query->where(function($q) use ($user){
                    $q->where('resto.id_regional', $user->location_now);
                });
            }
        $MasterAsset = $query->get();
        // Add a "No" column as the first column
        $MasterAsset = $MasterAsset->map(function ($row, $key) {
            return [
                'No' => $key + 1,
                'Asset Tag' => $row->register_code,
                'Reason' => "STOCK OPNAME",
                'Prioritas' => null,
                'Kategori' => null,
                'Tipe' => null,
                'Satuan' => null,
            ];
        });
    
        return $MasterAsset;
    }
    
    

    public function headings(): array {
    
        return [
            'No',            // Custom column for numbering
            'Asset Tag',
            'Reason',
            'Condition',
            'Description'
        ];
    }
}
