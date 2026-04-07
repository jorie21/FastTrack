<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardRepository $repo
    ) {}

    public function stats(Request $request): JsonResponse
    {
        return response()->json($this->repo->getStats($request));
    }

    public function todayStats(Request $request): JsonResponse
    {
        return response()->json($this->repo->getTodayStats($request));
    }

    public function cashFlow(Request $request): JsonResponse
    {
        return response()->json($this->repo->getCashFlow($request));
    }

    public function topSpending(Request $request): JsonResponse
    {
        return response()->json($this->repo->getTopSpending($request));
    }

    public function recentTransactions(Request $request): JsonResponse
    {
        return response()->json($this->repo->getRecentTransactions($request));
    }
}
