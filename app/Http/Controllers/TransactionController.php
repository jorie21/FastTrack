<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoretransanctionRequest;
use App\Http\Requests\UpdatetransanctionRequest;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionRepository $repo
    ) {}

    public function store(StoretransanctionRequest $request): JsonResponse
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

    public function update(UpdatetransanctionRequest $request, string $uuid): JsonResponse
    {
        return response()->json($this->repo->update($request, $uuid));
    }
}
