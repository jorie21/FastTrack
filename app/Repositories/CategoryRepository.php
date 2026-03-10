<?php

namespace App\Repositories;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

Class CategoryRepository
{
    private $title = 'category';

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $data = [
                'uuid' => Str::uuid()->toString(),
                'name' => $request->name ?? null,
                'icon' => $request->icon ?? null,
                'color' => $request->color ?? null,
                'type' => $request->type ?? 'expense',
                'user_id' => $request->user()->id ?? null,
            ];

            $category = Category::create($data);

            DB::commit();

            return $category;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function get($request)
    {
        return Category::query()->filterCategoryByRequest($request)->sort()->get();
    }

    public function delete($request, $uuid)
    {
        try {
            DB::beginTransaction();

            $category = Category::where('uuid', $uuid)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $category->delete();

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

            $category = Category::where('uuid', $uuid)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $category->update([
                'name' => $request->name ?? $category->name,
                'icon' => $request->icon ?? $category->icon,
                'color' => $request->color ?? $category->color,
                'type' => $request->type ?? $category->type,
            ]);

            DB::commit();

            return $category;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
}