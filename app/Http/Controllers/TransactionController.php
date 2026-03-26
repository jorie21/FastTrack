<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\TransactionRepository;
use App\Http\Requests\StoretransanctionRequest;
use App\Http\Requests\UpdatetransanctionRequest;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $repo;

    public function __construct(TransactionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store(StoretransanctionRequest $request)
    {
        return response()->json($this->repo->store($request));
    }

    public function get(Request $request)
    {
        return response()->json($this->repo->get($request));
    }

    public function delete(Request $request, $uuid)
    {
        return response()->json($this->repo->delete($request, $uuid));
    }

    public function update(UpdatetransanctionRequest $request, $uuid)
    {
        return response()->json($this->repo->update($request, $uuid));
    }
}