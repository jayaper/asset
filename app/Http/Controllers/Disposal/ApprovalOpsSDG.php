<?php

namespace App\Http\Controllers\Disposal;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterDisOut;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalOpsSDG extends Controller
{
    public function index()
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $query = DB::table('t_out')
            ->select(
                't_out.*',
                'b.qty',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'fromloc.name_store_street AS from_location'
            )
            ->leftjoin(DB::RAW('(
                SELECT
                    b.out_id, 
                    SUM(b.qty) AS qty
                FROM t_out_detail AS b
                GROUP BY b.out_id) AS b'), 'b.out_id', '=', 't_out.out_id')

            ->leftjoin('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->leftjoin('mc_approval', 't_out.appr_3', '=', 'mc_approval.approval_id')
            ->leftjoin(
                'miegacoa_keluhan.master_resto AS fromloc',
                DB::raw('CONVERT(t_out.from_loc USING utf8mb4) COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('CONVERT(fromloc.id USING utf8mb4) COLLATE utf8mb4_unicode_ci')
            )
            ->whereIn('t_out.appr_3', ['1', '2', '3', '4'])
            ->whereNull('t_out.deleted_at')
            ->where('t_out.out_id', 'like', 'DA%')
            ->orderBy('t_out.out_id', 'DESC');
            // Jika yang login bukan admin, tambahkan filter berdasarkan `user_loc`
            $user = Auth::User();
            if ($user->hasRole('SM')) {
                $query->where(function ($q){
                    $q->where('t_out.from_loc', Auth::User()->location_now);
                });
            }else if($user->hasRole('AM')) {
                $query->where(function ($q){
                    $q->where('fromloc.kode_city', Auth::User()->location_now);
                });
            }else if($user->hasRole('RM')) {
                $query->where(function ($q){
                    $q->where('fromloc.id_regional', Auth::User()->location_now);
                });
            }
        $moveouts = $query->paginate(10);

        return view("disposal.apprdis-sdgasset", [
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveouts' => $moveouts
        ]);
    }

    public function Update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'appr_3' => 'required|string|max:255',
        ]);

        // Cek apakah MoveOut dengan id yang benar ada
        $moveout = MasterDisOut::find($id); // Langsung gunakan find jika ID adalah primary key

        if (!$moveout) {
            return response()->json(['status' => 'error', 'message' => 'moveout not found.'], 404);
        }

        // Update data moveout
        $moveout->appr_3_date = Carbon::now();
        $moveout->confirm_date = Carbon::now();
        $moveout->appr_3 = $request->appr_3;
        $moveout->appr_3_user = auth()->user()->username;
        if ($request->appr_3 == '3') {
            $moveout->is_confirm = '3';
            $moveout->dest_loc = $moveout->from_loc;
            $moveoutDetails = DB::table('t_out_detail')->where('out_id', $id)->get();

            foreach ($moveoutDetails as $detail) {
                $t_regist = DB::table('table_registrasi_asset')
                        ->where('register_code', $detail->asset_tag)
                        ->get();
    
                    foreach($t_regist as $table){
                        DB::table('table_registrasi_asset')
                        ->where('id', $table->id)
                        ->update([
                            'last_transaction_code' => $id,
                            'status_asset' => 4
                        ]);
                    }
                    
                $newQty = max(0, $detail->qty - 1);
                DB::table('t_out_detail')
                    ->where('id', $detail->id)
                    ->update([
                        'qty' => $newQty,
                    ]);

                DB::table('asset_tracking')->insert([
                    'start_date' => $moveout->created_at,
                    'from_loc' => $moveout->from_loc,
                    'end_date' => Carbon::now(),
                    'dest_loc' => $moveout->dest_loc,
                    'reason' => $moveout->reason_id,
                    'description' => $moveout->out_desc,
                    'register_code' => $detail->asset_tag,
                    'out_id' => $id,
                ]);
            }
        } elseif ($request->appr_3 == '4') {
            $moveout->is_confirm = '4';
    
            DB::table('t_out_detail')
                ->where('out_id', $id)
                ->update(['status' => 4]);
    
            $details = DB::table('t_out_detail')
                ->where('out_id', $id)
                ->get();
    
                foreach ($details as $detail) {
                    if (!isset($detail->id)) {
                        continue;
                    }
    
                    // Reduce qty and store in qty_final
                    $newQty = max(0, $detail->qty - 1);
                    DB::table('t_out_detail')
                        ->where('id', $detail->id)
                        ->update([
                            'qty' => $newQty,
                        ]);
    
                    // Increment qty in table_registrasi_asset
                    $t_regist = DB::table('table_registrasi_asset')
                        ->where('register_code', $detail->asset_tag)
                        ->get();
    
                    foreach($t_regist as $table){
                        DB::table('table_registrasi_asset')
                        ->where('id', $table->id)
                        ->update([
                            'qty' => 1,
                            'status_asset' => 1
                        ]);
                    }
    
                }
        }
        
        if ($moveout->save()) { // Menggunakan save() yang lebih aman daripada update()
            return response()->json([
                'status' => 'success',
                'message' => 'moveout updated successfully.',
                'redirect_url' => '/disposal/approval-sdg-asset', // Sesuaikan dengan route index Anda
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update moveout.'], 500);
        }
    }
    public function DetailApprovalSdg($id) 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();

        $moveOutAssets = DB::table('t_out')
        ->select(
            't_out.*',
            't_out_detail.out_id AS detail_out_id',
            't_out_detail.qty',
            'm_reason.reason_name',
            'miegacoa_keluhan.master_resto.name_store_street AS from_location'
        )
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('miegacoa_keluhan.master_resto', 't_out.from_loc', '=', 'miegacoa_keluhan.master_resto.id')
        ->where('t_out.out_id', '=', $id) // Ensure specific match
        ->where('t_out.out_id', 'like', 'DA%')
        ->first();

        $assets = DB::table('table_registrasi_asset')
        ->leftjoin('t_out_detail', 'table_registrasi_asset.register_code', 't_out_detail.asset_tag')
        ->leftjoin('t_out', 't_out_detail.out_id', 't_out.out_id')
        ->leftjoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
        ->leftjoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
        ->leftjoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
        ->leftjoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
        ->select('m_assets.asset_model', 'm_brand.brand_name', 't_out_detail.qty', 'm_uom.uom_name', 'table_registrasi_asset.serial_number', 'table_registrasi_asset.register_code', 'm_condition.condition_name', 't_out_detail.image')
        ->where('t_out.out_id', 'like', 'DA%')
        ->where('t_out_detail.out_id', $id)
        ->get();

        // dd($moveOutAssets);

        return view('disposal.detail_data_disposal', compact('reasons', 'moveOutAssets', 'assets'));
    }

    public function HalamanReview() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();

        $restos = DB::table('miegacoa_keluhan.master_resto')->select('store_code', 'name_store_street')->get();

        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();

        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();

        

        $username = auth()->user()->username;

        $fromLoc = DB::table('m_people')

                ->where('nip', $username)

                ->value('loc_id'); 



        $registerLocation = DB::table('master_resto')

                ->where('store_code', $fromLoc)

                ->value('resto');

    

        // Filter assets based on the register_location matching the fetched resto

        $assets = DB::table('table_registrasi_asset')

        ->select('id', 'asset_name')

        // ->where('location_now', $registerLocation)

        ->where('qty', '>', 0) 

        ->get();       

        $user_loc = auth()->user()->location_now;
        $username = auth()->user()->username;

        // Mulai query builder
        $query = DB::table('t_out')
            ->distinct()
            ->select(
                't_out.*',
                't_out_detail.*',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'miegacoa_keluhan.master_resto.*',
                't_out_detail.*',
                'm_uom.uom_name',
                'm_brand.brand_name'
            )
            ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->join('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')
            ->join(
                'miegacoa_keluhan.master_resto',
                DB::raw('CONVERT(t_out.from_loc USING utf8mb4) COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('CONVERT(miegacoa_keluhan.master_resto.id USING utf8mb4) COLLATE utf8mb4_unicode_ci')
            )
            ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
            ->join('m_uom', 't_out_detail.uom', '=', 'm_uom.uom_id')
            ->join('m_brand', 't_out_detail.brand', '=', 'm_brand.brand_id')
            ->join(
                'm_user',
                DB::raw('CONVERT(t_out.from_loc USING utf8mb4) COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('CONVERT(m_user.location_now USING utf8mb4) COLLATE utf8mb4_unicode_ci')
            );

            // Jika yang login bukan admin, tambahkan filter berdasarkan `user_loc`
            if ($username != 'admin') {
                $query->where(
                DB::raw('CONVERT(m_user.location_now USING utf8mb4) COLLATE utf8mb4_unicode_ci'),
                '=', $user_loc);
            }

            $moveouts = $query->where('t_out.out_id', 'like', 'DA%')
            ->paginate(10);
    

        return view("Admin.review-disposal", [
            'fromLoc' => $fromLoc,
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveouts' => $moveouts,
            'restos' => $restos
        ]);
    }
}
