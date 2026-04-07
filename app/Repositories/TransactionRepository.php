<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Wallet;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionRepository extends BaseRepository
{
    protected string $model = Transaction::class;
    protected string $title = 'transaction';

    protected function applyFilters(Request $request): Builder
    {
        return $this->query()->filterTransactionByRequest($request)->sort();
    }

    public function store(Request $request, array $extraData = [])
    {
        return DB::transaction(function () use ($request) {
            $user = $request->user();
            $data = [
                'uuid' => Str::uuid()->toString(),
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'wallet_id' => $request->wallet_id,
                'transaction_date' => $request->transaction_date ?? now(),
                'amount' => $request->amount ?? 0,
                'description' => $request->description,
                'type' => $request->type ?? 'expense',
            ];

            if ($data['wallet_id']) {
                $this->updateWalletBalance($data['wallet_id'], $user->id, $data['amount'], $data['type'], 'apply');
            }

            return Transaction::create($data);
        });
    }

    public function update(Request $request, string $uuid)
    {
        return DB::transaction(function () use ($request, $uuid) {
            $user = $request->user();
            $transaction = $this->findByUuid($uuid, $user->id);

            // Reverse old balance
            if ($transaction->wallet_id) {
                $this->updateWalletBalance($transaction->wallet_id, $user->id, $transaction->amount, $transaction->type, 'reverse');
            }

            $newAmount = $request->amount ?? $transaction->amount;
            $newType = $request->type ?? $transaction->type;
            $newWalletId = $request->wallet_id ?? $transaction->wallet_id;

            // Apply new balance
            if ($newWalletId) {
                $this->updateWalletBalance($newWalletId, $user->id, $newAmount, $newType, 'apply');
            }

            $transaction->update([
                'category_id' => $request->category_id ?? $transaction->category_id,
                'wallet_id' => $newWalletId,
                'transaction_date' => $request->transaction_date ?? $transaction->transaction_date,
                'amount' => $newAmount,
                'description' => $request->description ?? $transaction->description,
                'type' => $newType,
            ]);

            return $transaction;
        });
    }

    public function delete(Request $request, string $uuid)
    {
        return DB::transaction(function () use ($request, $uuid) {
            $user = $request->user();
            $transaction = $this->findByUuid($uuid, $user->id);

            if ($transaction->wallet_id) {
                $this->updateWalletBalance($transaction->wallet_id, $user->id, $transaction->amount, $transaction->type, 'reverse');
            }

            $transaction->delete();

            return ['message' => "{$this->title} deleted successfully"];
        });
    }

    private function updateWalletBalance(int $walletId, int $userId, float $amount, string $type, string $action): void
    {
        $wallet = Wallet::where('id', $walletId)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->firstOrFail();

        $isIncome = $type === 'income';
        $isApply = $action === 'apply';
        
        $adjustment = $isIncome === $isApply ? $amount : -$amount;

        if (!$isIncome && $isApply && $wallet->name !== 'Cash' && $amount > $wallet->balance) {
            throw new Exception("Not enough balance in this wallet.");
        }

        $wallet->balance += $adjustment;
        $wallet->save();
    }
}
