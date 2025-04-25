<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Supplier extends Controller
{
    public function index()

    {

        $suppliers = DB::table('m_supplier')->select('m_supplier.*')->paginate(10);
        return view("master_data.supplier", ['suppliers' => $suppliers]);

    }

    public function NewAddDataSupplier(Request $request) {
        $request->validate([
            'supplier_code' => 'required|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'supplier_address' => 'required|string|max:255',
        ]);

        try {
            $supplier = new MasterSupplier();
            $supplier->supplier_code = $request->input('supplier_code');
            $supplier->supplier_name = $request->input('supplier_name');
            $supplier->supplier_address = $request->input('supplier_address');
            $supplier->create_by = Auth::user()->username;

            $maxSupplierId = MasterSupplier::max('supplier_id');
            $supplier->supplier_id = $maxSupplierId ? $maxSupplierId + 1 : 1;
            $supplier->create_date = Carbon::now();
            $supplier->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data supplier',
                'redirect_url' => '/master-data/supplier'
            ]);
        } catch (\Exception $e) {
            return response()->json([
               'status' => 'error',
               'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataSupplier(Request $request, $id)
    {
        $request->validate([
            'supplier_code' => 'required|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'supplier_address' => 'required|string|max:255',
        ]);

        $supplier = MasterSupplier::find($id);

        if (!$supplier) {
            return response()->json(['status' => 'error', 'message' => 'Supplier not found.'], 404);
        }

        $supplier->supplier_code = $request->input('supplier_code');
        $supplier->supplier_name = $request->input('supplier_name');
        $supplier->supplier_address = $request->input('supplier_address');

        if ($supplier->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data supplier',
                'redirect_url' => '/master-data/supplier'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Supplier.'], 500);
        }
    }

        public function NewDeleteDataSupplier($id) {
            $supplier = MasterSupplier::find($id);

            if ($supplier) {
                $supplier->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil menghapus supplier',
                    'redirect_url' => '/master-data/supplier'
                ]);
            } else {
                return response()->json(['status' => 'Error', 'message' => 'Data Supplier Gagal Terhapus'], 404);
        }
    }
}
