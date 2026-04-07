<?php

namespace App\Repositories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WalletRepository extends BaseRepository
{
    protected string $model = Wallet::class;
    protected string $title = 'wallet';

    protected function applyFilters(Request $request): Builder
    {
        return $this->query()->filterByRequest($request)->sort();
    }

    protected function mapStorageData(Request $request): array
    {
        return [
            'name' => $request->name,
            'type' => $request->type,
            'balance' => $request->balance ?? 0,
            'icon' => $request->icon ?? null,
            'color' => $request->color ?? null,
            'is_active' => $request->is_active ?? true,
        ];
    }

    protected function mapUpdateData(Request $request, Model $model): array
    {
        return [
            'name' => $request->name ?? $model->name,
            'type' => $request->type ?? $model->type,
            'balance' => $request->balance ?? $model->balance,
            'icon' => $request->icon ?? $model->icon,
            'color' => $request->color ?? $model->color,
            'is_active' => $request->is_active ?? $model->is_active,
        ];
    }

    protected function beforeDelete(Model $model): void
    {
        if ($model->transactions()->exists()) {
            abort(400, "This {$this->title} cannot be deleted because it is associated with one or more transactions.");
        }
    }
}
