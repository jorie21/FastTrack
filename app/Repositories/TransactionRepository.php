<?php

namespace App\Repositories;

use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionRepository
{
    private $title = "transaction";

    public function store($request)
    {
        $user = $request->user();

        try {
            DB::beginTransaction();

            $data = [
                'uuid' => Str::uuid()->toString(),
                'user_id' => $request->user_id ?? $user->id,
                'category_id' => $request->category_id ?? null,
                'transaction_date' => $request->transaction_date ?? now(),
                'amount' => $request->amount ?? 0,
                'description' => $request->description ?? null,
                'type' => $request->type ?? 'expense',
            ];

            $transaction = Transaction::create($data);

            DB::commit();

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to create {$this->title}: " . $e->getMessage());
        }
    }
    public function get($request)
    {
        return Transaction::query()->filterTransactionByRequest($request)->sort()->get();
    }
    public function delete($request, $uuid)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::where('uuid', $uuid)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $transaction->delete();

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

            $transaction = Transaction::where('uuid', $uuid)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $transaction->update([
                'category_id' => $request->category_id ?? $transaction->category_id,
                'transaction_date' => $request->transaction_date ?? $transaction->transaction_date,
                'amount' => $request->amount ?? $transaction->amount,
                'description' => $request->description ?? $transaction->description,
                'type' => $request->type ?? $transaction->type,
            ]);

            DB::commit();

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}