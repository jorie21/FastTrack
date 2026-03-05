<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store(Request $request)
    {
        return response()->json($this->repo->store($request));
    }

    public function get(Request $request)
    {
        return response()->json($this->repo->get($request));
    }

}
