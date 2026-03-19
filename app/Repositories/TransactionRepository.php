<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Wallet;
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
                'wallet_id' => $request->wallet_id ?? null,
                'transaction_date' => $request->transaction_date ?? now(),
                'amount' => $request->amount ?? 0,
                'description' => $request->description ?? null,
                'type' => $request->type ?? 'expense',
            ];

            // --- Wallet Balance Logic ---
            if ($data['wallet_id']) {
                $wallet = Wallet::where('id', $data['wallet_id'])
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($data['type'] === 'income') {
                    $wallet->balance += $data['amount'];
                } else {
                    // Expense Validation
                    if ($wallet->name !== 'Cash' && $data['amount'] > $wallet->balance) {
                        throw new Exception("Not enough balance in this wallet.");
                    }
                    $wallet->balance -= $data['amount'];
                }
                $wallet->save();
            }

            $transaction = Transaction::create($data);

            DB::commit();

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
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

            // Reverse wallet balance impact
            if ($transaction->wallet_id) {
                $wallet = Wallet::where('id', $transaction->wallet_id)
                    ->where('user_id', $request->user()->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($transaction->type === 'income') {
                    $wallet->balance -= $transaction->amount;
                } else {
                    $wallet->balance += $transaction->amount;
                }
                $wallet->save();
            }

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

            $oldAmount = $transaction->amount;
            $oldType = $transaction->type;
            $oldWalletId = $transaction->wallet_id;

            $newAmount = $request->amount ?? $oldAmount;
            $newType = $request->type ?? $oldType;
            $newWalletId = $request->wallet_id ?? $oldWalletId;

            // Reverse old impact
            if ($oldWalletId) {
                $oldWallet = Wallet::where('id', $oldWalletId)
                    ->where('user_id', $request->user()->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($oldType === 'income') {
                    $oldWallet->balance -= $oldAmount;
                } else {
                    $oldWallet->balance += $oldAmount;
                }
                $oldWallet->save();
            }

            // Apply new impact
            if ($newWalletId) {
                $newWallet = ($newWalletId == $oldWalletId) ? $oldWallet : Wallet::where('id', $newWalletId)
                    ->where('user_id', $request->user()->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($newType === 'income') {
                    $newWallet->balance += $newAmount;
                } else {
                    // Validation for expense
                    if ($newWallet->name !== 'Cash' && $newAmount > $newWallet->balance) {
                        throw new Exception("Not enough balance in this wallet.");
                    }
                    $newWallet->balance -= $newAmount;
                }
                $newWallet->save();
            }

            $transaction->update([
                'category_id' => $request->category_id ?? $transaction->category_id,
                'wallet_id' => $newWalletId,
                'transaction_date' => $request->transaction_date ?? $transaction->transaction_date,
                'amount' => $newAmount,
                'description' => $request->description ?? $transaction->description,
                'type' => $newType,
            ]);

            DB::commit();

            return $transaction;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
