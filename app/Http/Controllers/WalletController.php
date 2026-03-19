<?php

namespace App\Http\Controllers;

use App\Repositories\WalletRepository;
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

    public function store(Request $request)
    {
        return response()->json($this->repo->store($request));
    }

    public function update(Request $request, $uuid)
    {
        return response()->json($this->repo->update($request, $uuid));
    }

    public function delete(Request $request, $uuid)
    {
        return response()->json($this->repo->delete($request, $uuid));
    }
}
