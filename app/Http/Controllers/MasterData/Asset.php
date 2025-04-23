<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterAsset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Asset extends Controller
{
    public function HalamanAssets()
    {

        $priorities = DB::table('m_priority')->select('priority_id', 'priority_name')->get();

        $categories = DB::table('m_category')->select('cat_id', 'cat_name')->get();

        $tipies = DB::table('m_type')->select('type_id', 'type_name')->get();

        $uomies = DB::table('m_uom')->select('uom_id', 'uom_name')->get();



        $assets = DB::table('m_assets')
        ->select('m_assets.*', 'm_priority.priority_name', 'm_category.*', 'm_type.*', 'm_uom.*')
        ->leftjoin('m_priority', 'm_assets.priority_id', '=', 'm_priority.priority_id')
        ->leftjoin('m_category', 'm_assets.cat_id', '=', 'm_category.cat_id')
        ->leftjoin('m_type', 'm_assets.type_id', '=', 'm_type.type_id')
        ->leftjoin('m_uom', 'm_assets.uom_id', '=', 'm_uom.uom_id')
        ->where('m_assets.type_id', 1) // Filter for equipment
        ->get();



        return view("master_data.asset", [

            'priorities' => $priorities,

            'categories' => $categories,

            'tipies' => $tipies,

            'uomies' => $uomies,

            'assets' => $assets

        ]);

    }

    public function NewAddDataAsset(Request $request) {
        $request->validate([
            'asset_code' => 'required|string|max:255',

            'asset_model' => 'required|string|max:255',

            'asset_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

            'priority_id' => 'required|exists:m_priority,priority_id',

            'cat_id' => 'required|exists:m_category,cat_id',

            'type_id' => 'required|exists:m_type,type_id',

            'uom_id' => 'required|exists:m_uom,uom_id',
        ]);

        try {
            $asset = new MasterAsset();

            $asset->asset_code = $request->input('asset_code');
            $asset->asset_model = $request->input('asset_model');
            $asset->asset_status = $request->input('asset_status');
            $asset->asset_quantity = $request->input('asset_quantity');

            if ($request->hasFile('asset_image')) {
                $image = $request->file('asset_image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('assets/images'), $imageName);
                $asset->asset_image = 'assets/images/'.$imageName;
            }

            $asset->priority_id = $request->input('priority_id');
            $asset->cat_id = $request->input('cat_id');
            $asset->type_id = $request->input('type_id');
            $asset->uom_id = $request->input('uom_id');
            $asset->create_by = Auth::user()->username;

            $maxAssetId = MasterAsset::max('asset_id');

            $asset->asset_id = $maxAssetId ? $maxAssetId + 1 : 1;

            $asset->create_date = Carbon::now();

            $asset->save();

            return response()->json([
                'status' => 'success',
                "message" => "Data Asset Berhasil Ditambahkan",
                'redirect_url' => url('/master-data/asset')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal tambah data asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function NewUpdateDataAssets(Request $request, $id) {
        $request->validate([
            'asset_code' => 'required|string|max:255',
            'asset_model' => 'required|string|max:255',
            'asset_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'priority_id' => 'required|exists:m_priority,priority_id',
            'cat_id' => 'required|exists:m_category,cat_id',
            'type_id' => 'required|exists:m_type,type_id',
            'uom_id' => 'required|exists:m_uom,uom_id',
        ]);

        $asset = MasterAsset::find($id);

        if (!$asset) {
            return response()->json(['status' => 'error', 'message' => 'asset not found.'], 404);
        }

        $asset->asset_code = $request->asset_code;
        $asset->asset_model = $request->asset_model;
        $asset->asset_status = $request->asset_status;
        $asset->asset_quantity = $request->asset_quantity;

        return $this->extracted($request, $asset);
    }

    public function NewUpdateDataAssetsStatus(Request $request, $id) {

        $request->validate([
            'asset_code' => 'required|string|max:255',

            'asset_model' => 'required|string|max:255',

            // 'asset_status' => 'required|string|max:255',

            // 'asset_quantity' => 'required|string|max:255',

            'asset_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'priority_id' => 'required|string|max:255',

            'cat_id' => 'required|string|max:255',

            'type_id' => 'required|string|max:255',

            'uom_id' => 'required|string|max:255',
        ]);

        $asset = MasterAsset::find($id);

        if (!$asset) {
            return response()->json(['status' => 'error', 'message' => 'asset not found.'], 404);
        }

        $asset->asset_code = $request->asset_code;
        $asset->asset_model = $request->asset_model;
        $asset->asset_status = $request->asset_status;
        $asset->asset_quantity = $request->asset_quantity;

        return $this->extracted($request, $asset);
    }

    public function NewDeleteDataAssets($id) {
        $asset = MasterAsset::find($id);

        if ($asset) {
            $asset->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Asset Berhasil Dihapus',
                'redirect_url' => url('/master-data/asset')
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data Asset not found.'], 404);
        }
    }

    /**
     * @param Request $request
     * @param $asset
     * @return \Illuminate\Http\JsonResponse
     */
    public function extracted(Request $request, $asset): \Illuminate\Http\JsonResponse
    {
        if ($request->hasFile('asset_image')) {
            $image = $request->file('asset_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images'), $imageName);
            $asset->asset_image = 'assets/images/' . $imageName;
        }
        $asset->priority_id = $request->priority_id;
        $asset->cat_id = $request->cat_id;
        $asset->type_id = $request->type_id;
        $asset->uom_id = $request->uom_id;

        if ($asset->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data Asset Berhasil Diubah',
                'redirect_url' => url('/master-data/asset')
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update asset.'], 500);
        }
    }
}
