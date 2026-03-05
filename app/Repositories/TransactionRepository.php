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
                'created_by_id' => $user->id,
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
    public function delete($request)
    {
        //code..
    }
}