<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardRepository
{
    public function getStats(Request $request)
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
        $netBalance = $totalIncome - $totalExpense;
        $savingsRate = $totalIncome > 0 ? round((($totalIncome - $totalExpense) / $totalIncome) * 100) : 0;

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_balance' => $netBalance,
            'savings_rate' => $savingsRate,
        ];
    }

    public function getCashFlow(Request $request)
    {
        $user = $request->user();
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M');
            $year = $date->year;
            $monthNum = $date->month;

            $stats = Transaction::query()
                ->getByUser($user->id)
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $monthNum)
                ->selectRaw("
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
                ")
                ->first();

            $data[] = [
                'label' => $month,
                'income' => (float) ($stats->income ?? 0),
                'expense' => (float) ($stats->expense ?? 0),
            ];
        }

        return $data;
    }

    public function getTopSpending(Request $request)
    {
        $user = $request->user();
        
        $totalExpense = Transaction::query()
            ->getByUser($user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        if ($totalExpense <= 0) return [];

        $categories = Transaction::query()
            ->getByUser($user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->selectRaw('category_id, SUM(amount) as total_amount')
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->with('category')
            ->limit(5)
            ->get();

        return $categories->map(function ($item) use ($totalExpense) {
            return [
                'name' => $item->category->name ?? 'Uncategorized',
                'icon' => $item->category->icon ?? 'Package',
                'color' => $item->category->color ?? '#63d478',
                'amount' => (float) $item->total_amount,
                'pct' => round(($item->total_amount / $totalExpense) * 100),
            ];
        });
    }

    public function getRecentTransactions(Request $request)
    {
        $user = $request->user();

        $transactions = Transaction::query()
            ->getByUser($user->id)
            ->with('category')
            ->latest('transaction_date')
            ->limit(7)
            ->get();

        return $transactions->map(function ($txn) {
            $date = $txn->transaction_date;
            if (is_string($date)) {
                $date = \Illuminate\Support\Carbon::parse($date);
            }

            return [
                'id' => $txn->id,
                'title' => $txn->description ?? 'Untitled',
                'category' => $txn->category->name ?? 'General',
                'categoryColor' => $txn->category->color ?? '#63d478',
                'icon' => $txn->category->icon ?? 'CreditCard',
                'amount' => (float) $txn->amount,
                'type' => $txn->type,
                'date' => $date ? $date->format('M j') : '',
            ];
        });
    }
}
