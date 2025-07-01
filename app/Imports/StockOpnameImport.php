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

            $reason_id = $this->getReasonId($reason_name);
            if (!$reason_id) {
                $this->errors[] = "Baris $rowIndex: Reason harus 'STOCK OPNAME'.";
                continue;
            }

            $location_id = $this->user->location_now;

            $condition_id = $this->getConditionId($condition_name);
            if (!$condition_id) {
                $this->errors[] = "Baris $rowIndex: Condition '$condition_name' tidak valid. Gunakan POOR, BROKEN, GOOD, atau MINT.";
                continue;
            }

            if (!$this->isValidAssetTag($asset_tag)) {
                $this->errors[] = "Baris $rowIndex: Asset tag '$asset_tag' asset sedang di tahap proses atau tidak sesuai lokasi.";
                continue;
            }

            // ✅ Cek apakah asset_tag sudah ada di t_stockopname hari ini
            $alreadyExistsToday = DB::table('t_stockopname')
                ->where('asset_tag', $asset_tag)
                ->whereDate('create_date', Carbon::now())
                ->exists();

            if ($alreadyExistsToday) {
                $this->errors[] = "Baris $rowIndex: Asset tag '$asset_tag' sudah pernah diinput hari ini.";
                continue;
            }

            $deletedAt = DB::table('table_registrasi_asset')
                ->where('register_code', $asset_tag)
                ->value('deleted_at');

            $trx_code = DB::table('t_trx')->where('trx_name', 'Stock Opname')->value('trx_code');
            $today = Carbon::now()->format('ymd');
            $todayCount = DB::table('t_stockopname')->whereDate('create_date', Carbon::now())->count() + 1;
            $transaction_number = str_pad($todayCount, 3, '0', STR_PAD_LEFT);
            $generated_code = "{$trx_code}.{$today}.{$reason_id}.{$location_id}.{$transaction_number}";

            $inserted = DB::table('t_stockopname')->insert([
                'code'        => $generated_code,
                'asset_tag'   => $asset_tag,
                'reason'      => $reason_id,
                'location'    => $location_id,
                'condition'   => $condition_id,
                'description' => $description,
                'create_date' => now(),
                'create_by'   => $this->user->username,
                'is_confirm'  => 1,
                'deleted_at'  => $deletedAt,
                'created_at'  => now(),
            ]);

            if ($inserted) {
                DB::table('table_registrasi_asset')
                    ->where('register_code', $asset_tag)
                    ->update([
                        'qty' => 0,
                        'status_asset' => 5
                    ]);
            }
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
