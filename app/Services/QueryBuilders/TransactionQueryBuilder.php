<?php

namespace App\Services\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class TransactionQueryBuilder extends Builder
{
    public function getByUser($value): self
    {
        return $this->where('user_id', $value);
    }

    public function getByCategory($value): self
    {
        return $this->where('category_id', $value);
    }

    public function getByWallet($value): self
    {
        return $this->where('wallet_id', $value);
    }

    public function getByDate($value): self
    {
        return $this->whereDate('transaction_date', $value);
    }

    public function getByType($value): self
    {
        return $this->where('type', $value);
    }

    public function search($keyword): self
    {
        if (empty($keyword)) return $this;

        return $this->where(function ($query) use ($keyword) {
            $query->where('description', 'LIKE', "%{$keyword}%")
                ->orWhere('amount', 'LIKE', "%{$keyword}%");
        });
    }

    public function sort($sort = 'desc'): self
    {
        return $this->orderBy('transaction_date', $sort);
    }

    public function filterTransactionByRequest($request): self
    {
        return $this->getByUser($request->user()->id)
            ->when($request->category_id && $request->category_id !== 'all', fn($q) => $q->getByCategory($request->category_id))
            ->when($request->wallet_id && $request->wallet_id !== 'all', fn($q) => $q->getByWallet($request->wallet_id))
            ->when($request->transaction_date, fn($q) => $q->getByDate($request->transaction_date))
            ->when($request->type && $request->type !== 'all', fn($q) => $q->getByType($request->type))
            ->when($request->keyword, fn($q) => $q->search($request->keyword));
    }
}
