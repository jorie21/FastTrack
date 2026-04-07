<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class BaseRepository
{
    /**
     * The model class for this repository.
     */
    protected string $model;

    /**
     * The display name for the entity (e.g., 'category').
     */
    protected string $title;

    /**
     * Get a new query builder for the model.
     */
    protected function query(): Builder
    {
        return app($this->model)->query();
    }

    /**
     * Get all records filtered and sorted.
     */
    public function get(Request $request): Collection
    {
        return $this->applyFilters($request)->get();
    }

    /**
     * Get paginated records.
     */
    public function paginate(Request $request, int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters($request)->paginate($request->per_page ?? $perPage);
    }

    /**
     * Apply common filters and sorting.
     */
    protected function applyFilters(Request $request): Builder
    {
        return $this->query()
            ->when(method_exists(app($this->model), 'scopeFilterByRequest'), fn($q) => $q->filterByRequest($request))
            ->sort();
    }

    /**
     * Find a record by its UUID and User ID.
     */
    public function findByUuid(string $uuid, int $userId): Model
    {
        return $this->query()
            ->where('uuid', $uuid)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    /**
     * Standard store operation with UUID and user_id.
     */
    public function store(Request $request, array $extraData = [])
    {
        return DB::transaction(function () use ($request, $extraData) {
            $data = array_merge([
                'uuid' => Str::uuid()->toString(),
                'user_id' => $request->user()->id,
            ], $this->mapStorageData($request), $extraData);

            return $this->query()->create($data);
        });
    }

    /**
     * Standard update operation.
     */
    public function update(Request $request, string $uuid)
    {
        return DB::transaction(function () use ($request, $uuid) {
            $model = $this->findByUuid($uuid, $request->user()->id);
            $model->update($this->mapUpdateData($request, $model));
            return $model;
        });
    }

    /**
     * Standard delete operation.
     */
    public function delete(Request $request, string $uuid)
    {
        return DB::transaction(function () use ($request, $uuid) {
            $model = $this->findByUuid($uuid, $request->user()->id);
            
            $this->beforeDelete($model);
            
            $model->delete();

            return ['message' => "{$this->title} deleted successfully"];
        });
    }

    /**
     * Map request data for storage. Override in child classes.
     */
    protected function mapStorageData(Request $request): array
    {
        return $request->all();
    }

    /**
     * Map request data for update. Override in child classes.
     */
    protected function mapUpdateData(Request $request, Model $model): array
    {
        return $request->all();
    }

    /**
     * Hook before deletion.
     */
    protected function beforeDelete(Model $model): void
    {
        // 
    }
}
