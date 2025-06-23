<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportAssetRegist implements FromCollection, WithHeadings
{
    public function collection()
    {
        $dataAssets = DB::table('table_registrasi_asset')
        ->select(
            'table_registrasi_asset.id',
            'table_registrasi_asset.register_code',
            'm_assets.asset_model as asset_name',
            'table_registrasi_asset.serial_number',
            'm_type.type_name as type_asset',
            'm_category.cat_name as category_asset',
            'm_priority.priority_name as prioritas',
            'm_brand.brand_name',
            'm_status_asset.name',
            'm_uom.uom_name as satuan',
            'table_registrasi_asset.width',
            'table_registrasi_asset.height',
            'table_registrasi_asset.depth',
            'miegacoa_keluhan.master_resto.name_store_street as register_location',
            'loc_now.name_store_street as lokasi_sekarang',
            'm_layout.layout_name as layout',
            'table_registrasi_asset.register_date',
            'm_supplier.supplier_name as supplier',
            'm_condition.condition_name as condition',
            'table_registrasi_asset.purchase_number',
            'table_registrasi_asset.purchase_date',
            'm_warranty.warranty_name as warranty',
            'm_periodic_mtc.periodic_mtc_name as periodic_maintenance',
            'table_registrasi_asset.deleted_at'
        )
        ->leftJoin('m_status_asset', 'table_registrasi_asset.status_asset', '=', 'm_status_asset.id')
        ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
        ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
        ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
        ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
        ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
        ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
        ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
        ->leftJoin('miegacoa_keluhan.master_resto AS loc_now', 'table_registrasi_asset.location_now', '=', 'loc_now.id')
        ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
        ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
        ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
        ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
        ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id');
        $user = Auth::User();
        if($user->hasRole('SM')){
            $dataAssets->where(function($q) use ($user) {
                $q->where('table_registrasi_asset.location_now', $user->location_now);
            });
        }else if($user->hasRole('AM')){
            $dataAssets->where(function($q) use ($user) {
                $q->where('miegacoa_keluhan.master_resto.kode_city', $user->location_now);
            });
        }else if($user->hasRole('RM')){
            $dataAssets->where(function($q) use ($user) {
                $q->where('miegacoa_keluhan.master_resto.id_regional', $user->location_now);
            });
        }
        $dataAsset = $dataAssets->get();
    

        foreach ($dataAsset as $Asset) {
            if (!empty($Asset->asset_code)) {
                $qrCodeFileName = $Asset->asset_code . '.png';
                $qrCodeFilePath = storage_path('app/public/qrcodes/' . $qrCodeFileName);

                if (file_exists($qrCodeFilePath)) {
                    $Asset->qr_code_path = asset('storage/qrcodes/' . $qrCodeFileName);
                } else {
                    QrCode::format('png')->size(300)->generate($Asset->asset_code, $qrCodeFilePath);
                    $Asset->qr_code_path = asset('storage/qrcodes/' . $qrCodeFileName);
                }
            }
        }

        // Return the collection of assets
        return collect($dataAsset);
    }

    public function headings(): array
    {
        return [
            'Id',
            'Register Code',
            'Asset Name',
            'Serial Number',
            'Type Asset',
            'Category Asset',
            'Prioritas',
            'Merk',
            'Status Asset',
            'Satuan',
            'Width',
            'Height',
            'Depth',
            'Register Location',
            'Location Now',
            'Layout',
            'Register Date',
            'Supplier',
            'Condition',
            'Purchase Number',
            'Purchase Date',
            'Warranty',
            'Periodic Maintenance',
            'Status Deleted'
        ];
    }
}
