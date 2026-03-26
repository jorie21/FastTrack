<?php

namespace App\Repositories;

use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletRepository
{
    private $title = 'wallet';

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $data = [
                'uuid' => Str::uuid()->toString(),
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'type' => $request->type,
                'balance' => $request->balance ?? 0,
                'icon' => $request->icon ?? null,
                'color' => $request->color ?? null,
                'is_active' => $request->is_active ?? true,
            ];

            $wallet = Wallet::create($data);

            DB::commit();

            return $wallet;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function get($request)
    {
        return Wallet::query()->filterByRequest($request)->sort()->get();
    }

    public function delete($request, $uuid)
    {
        try {
            DB::beginTransaction();

            $wallet = Wallet::where('uuid', $uuid)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            if ($wallet->transactions()->exists()) {
                abort(400, "This {$this->title} cannot be deleted because it is associated with one or more transactions.");
            }

            $wallet->delete();

            DB::commit();

            return ['message' => "{$this->title} deleted successfully"];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($request, $uuid)
    {
        try {
            DB::beginTransaction();

            $wallet = Wallet::where('uuid', $uuid)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $wallet->update([
                'name' => $request->name ?? $wallet->name,
                'type' => $request->type ?? $wallet->type,
                'balance' => $request->balance ?? $wallet->balance,
                'icon' => $request->icon ?? $wallet->icon,
                'color' => $request->color ?? $wallet->color,
                'is_active' => $request->is_active ?? $wallet->is_active,
            ]);

            DB::commit();

            return $wallet;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
