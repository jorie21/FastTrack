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
                'description' => $request->description ?? null,
                'created_by_id' => $request->user()->id ?? null,
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

    public function delete($request)
    {
        //code
    }
    
}