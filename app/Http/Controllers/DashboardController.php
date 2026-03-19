<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    public function stats(Request $request)
    {
        return response()->json($this->repo->getStats($request));
    }

    public function todayStats(Request $request)
    {
        return response()->json($this->repo->getTodayStats($request));
    }

    public function cashFlow(Request $request)
    {
        return response()->json($this->repo->getCashFlow($request));
    }

    public function topSpending(Request $request)
    {
        return response()->json($this->repo->getTopSpending($request));
    }

    public function recentTransactions(Request $request)
    {
        return response()->json($this->repo->getRecentTransactions($request));
    }
}
