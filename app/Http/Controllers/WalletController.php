<?php

namespace App\Http\Controllers;

use App\Repositories\WalletRepository;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected $repo;

    public function __construct(WalletRepository $repo)
    {
        $this->repo = $repo;
    }

    public function get(Request $request)
    {
        return response()->json($this->repo->get($request));
    }

    public function store(StoreWalletRequest $request)
    {
        return response()->json($this->repo->store($request));
    }

    public function update(UpdateWalletRequest $request, $uuid)
    {
        return response()->json($this->repo->update($request, $uuid));
    }

    public function delete(Request $request, $uuid)
    {
        return response()->json($this->repo->delete($request, $uuid));
    }
}
