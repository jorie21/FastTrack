<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardRepository
{
    public function getStats(Request $request): array
    {
        $user = $request->user();

        $stats = Transaction::query()
            ->getByUser($user->id)
            ->selectRaw("
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
            ")
            ->first();

        $totalIncome = (float) ($stats->total_income ?? 0);
        $totalExpense = (float) ($stats->total_expense ?? 0);
        
        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_balance' => $totalIncome - $totalExpense,
            'savings_rate' => $totalIncome > 0 ? round((($totalIncome - $totalExpense) / $totalIncome) * 100) : 0,
        ];
    }

    public function getTodayStats(Request $request): array
    {
        $user = $request->user();
        $today = now()->format('Y-m-d');

        $stats = Transaction::query()
            ->getByUser($user->id)
            ->getByDate($today)
            ->selectRaw("
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ")
            ->first();

        $income = (float) ($stats->income ?? 0);
        $expense = (float) ($stats->expense ?? 0);

        return [
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
        ];
    }

    public function getCashFlow(Request $request): array
    {
        $user = $request->user();
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            
            $stats = Transaction::query()
                ->getByUser($user->id)
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->selectRaw("
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
                ")
                ->first();

            $data[] = [
                'label' => $date->format('M'),
                'income' => (float) ($stats->income ?? 0),
                'expense' => (float) ($stats->expense ?? 0),
            ];
        }

        return $data;
    }

    public function getTopSpending(Request $request): array
    {
        $user = $request->user();
        $now = now();
        
        $totalExpense = Transaction::query()
            ->getByUser($user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        if ($totalExpense <= 0) return [];

        return Transaction::query()
            ->getByUser($user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->selectRaw('category_id, SUM(amount) as total_amount')
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->with('category')
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'name' => $item->category->name ?? 'Uncategorized',
                'icon' => $item->category->icon ?? 'Package',
                'color' => $item->category->color ?? '#63d478',
                'amount' => (float) $item->total_amount,
                'pct' => round(($item->total_amount / $totalExpense) * 100),
            ])
            ->toArray();
    }

    public function getRecentTransactions(Request $request): array
    {
        $user = $request->user();

        return Transaction::query()
            ->getByUser($user->id)
            ->with('category')
            ->latest('transaction_date')
            ->limit(7)
            ->get()
            ->map(function ($txn) {
                $date = $txn->transaction_date instanceof Carbon 
                    ? $txn->transaction_date 
                    : Carbon::parse($txn->transaction_date);

                return [
                    'id' => $txn->id,
                    'title' => $txn->description ?? 'Untitled',
                    'category' => $txn->category->name ?? 'General',
                    'categoryColor' => $txn->category->color ?? '#63d478',
                    'icon' => $txn->category->icon ?? 'CreditCard',
                    'amount' => (float) $txn->amount,
                    'type' => $txn->type,
                    'date' => $date->format('M j'),
                ];
            })
            ->toArray();
    }
}
