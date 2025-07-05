<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class StockOpnameImport implements ToCollection
{
    protected $user;
    public $errors = [];

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function collection(Collection $rows)
    {
        $isFirst = true;
        $rowIndex = 1;

        $location_id = $this->user->location_now;
        $trx_code = DB::table('t_trx')->where('trx_name', 'Stock Opname')->value('trx_code');
        $today = Carbon::now()->format('ymd');
        $todayCount = DB::table('t_stockopname')->whereDate('create_date', Carbon::now())->count() + 1;
        $transaction_number = str_pad($todayCount, 3, '0', STR_PAD_LEFT);
        $so_code = "{$trx_code}.{$today}.2.{$location_id}.{$transaction_number}";

        $description = null;
        $reason_id = null;
        $detailData = [];

        foreach ($rows as $row) {
            $rowIndex++;
            if ($isFirst) {
                $isFirst = false;
                continue;
            }

            $asset_tag      = $row[1] ?? null;
            $reason_name    = $row[2] ?? null;
            $condition_name = $row[3] ?? null;
            $description    = $row[4] ?? null;

            if ($reason_id === null) {
                $reason_id = $this->getReasonId($reason_name);
                if (!$reason_id) {
                    $this->errors[] = "Baris $rowIndex: Reason harus 'STOCK OPNAME'.";
                    continue;
                }
            }

            $condition_id = $this->getConditionId($condition_name);
            if (!$condition_id) {
                $this->errors[] = "Baris $rowIndex: Condition '$condition_name' tidak valid. Gunakan POOR, BROKEN, GOOD, atau MINT.";
                continue;
            }

            if (!$this->isValidAssetTag($asset_tag)) {
                $this->errors[] = "Baris $rowIndex: Asset tag '$asset_tag' sedang diproses atau tidak berada di lokasi Anda.";
                continue;
            }

            $alreadyExistsToday = DB::table('t_stockopname_detail')
                ->where('asset_tag', $asset_tag)
                ->whereDate('created_at', Carbon::now())
                ->exists();

            if ($alreadyExistsToday) {
                $this->errors[] = "Baris $rowIndex: Asset tag '$asset_tag' sudah pernah diinput hari ini.";
                continue;
            }

            $detailData[] = [
                'so_code'    => $so_code,
                'asset_tag'  => $asset_tag,
                'condition'  => $condition_id,
                'created_at' => now()
            ];
        }

        if (empty($detailData)) {
            $this->errors[] = 'Tidak ada asset valid untuk diproses.';
            return;
        }

        // Ambil deleted_at dari salah satu asset valid (jika ada)
        $deletedAt = DB::table('table_registrasi_asset')
            ->where('register_code', $detailData[0]['asset_tag'])
            ->value('deleted_at');

        // Insert ke t_stockopname (sekali saja)
        DB::table('t_stockopname')->insert([
            'code'        => $so_code,
            'reason'      => $reason_id,
            'location'    => $location_id,
            'description' => $description,
            'is_verify'   => 1,
            'qty'         => count($detailData),
            'create_date' => now(),
            'create_by'   => $this->user->username,
            'is_confirm'  => 1,
            'deleted_at'  => $deletedAt,
            'created_at'  => now(),
        ]);

        // Insert semua detail sekaligus
        DB::table('t_stockopname_detail')->insert($detailData);

        // Update asset satu per satu
        foreach ($detailData as $detail) {
            DB::table('table_registrasi_asset')
                ->where('register_code', $detail['asset_tag'])
                ->update([
                    'qty' => 0,
                    'status_asset' => 5
                ]);
        }
    }

    private function getReasonId($name)
    {
        if (strtoupper(trim($name)) !== 'STOCK OPNAME') {
            return null;
        }

        return DB::table('m_reason')
            ->where('reason_name', 'STOCK OPNAME')
            ->value('reason_id');
    }

    private function getConditionId($name)
    {
        $allowed = ['POOR', 'BROKEN', 'GOOD', 'MINT'];
        $upper = strtoupper(trim($name));
        if (!in_array($upper, $allowed)) {
            return null;
        }

        return DB::table('m_condition')
            ->where('condition_name', $upper)
            ->value('condition_id');
    }

    private function isValidAssetTag($asset_tag)
    {
        return DB::table('table_registrasi_asset')
            ->where('register_code', $asset_tag)
            ->where('qty', 1)
            ->where('location_now', $this->user->location_now)
            ->exists();
    }
}
