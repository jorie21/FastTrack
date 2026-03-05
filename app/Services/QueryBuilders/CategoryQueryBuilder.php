<?php

namespace App\Services\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class CategoryQueryBuilder extends Builder
{
    public function getByUser($value): self
    {
        return $this->where('user_id', $value);
    }

    public function search($keyword): self
    {
        if (empty($keyword)) return $this;

        return $this->where('name', 'LIKE', "%{$keyword}%");
    }

    public function sort($sort = 'asc'): self
    {
        return $this->orderBy('name', $sort);
    }

    public function filterCategoryByRequest($request): self
    {
        return $this->getByUser($request->user()->id)
            ->when($request->keyword, fn($q) => $q->search($request->keyword));
    }
}
