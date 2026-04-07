<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function __construct(
        protected CategoryRepository $repo
    ) {}

    public function store(Request $request): JsonResponse
    {
        return response()->json($this->repo->store($request));
    }

    public function get(Request $request): JsonResponse
    {
        return response()->json($this->repo->paginate($request));
    }

    public function delete(Request $request, string $uuid): JsonResponse
    {
        return response()->json($this->repo->delete($request, $uuid));
    }

    public function update(Request $request, string $uuid): JsonResponse
    {
        return response()->json($this->repo->update($request, $uuid));
    }
}
