<?php

namespace App\Repositories;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CategoryRepository extends BaseRepository
{
    protected string $model = Category::class;
    protected string $title = 'category';

    protected function applyFilters(Request $request): Builder
    {
        return $this->query()->filterCategoryByRequest($request)->sort();
    }

    protected function mapStorageData(Request $request): array
    {
        return [
            'name' => $request->name,
            'icon' => $request->icon,
            'color' => $request->color,
            'type' => $request->type ?? 'expense',
        ];
    }

    protected function mapUpdateData(Request $request, Model $model): array
    {
        return [
            'name' => $request->name ?? $model->name,
            'icon' => $request->icon ?? $model->icon,
            'color' => $request->color ?? $model->color,
            'type' => $request->type ?? $model->type,
        ];
    }

    protected function beforeDelete(Model $model): void
    {
        $model->loadCount('transactions');

        if ($model->transactions_count > 0) {
            throw new Exception("Cannot delete category because it is used in {$model->transactions_count} transactions.");
        }
    }
}
