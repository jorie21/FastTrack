<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

import DashboardCashFlowChart      from './components/DashboardCashFlowChart.vue';
import DashboardGreetings          from './components/DashboardGreetings.vue';
import DashboardRecentTransactions from './components/DashboardRecentTransactions.vue';
import DashboardStatsCards         from './components/DashboardStatsCards.vue';
import DashboardTopSpending        from './components/DashboardTopSpending.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
];

// ── Mock data ────────────────────────────────────────────────────
const recentTransactions = [
    { id: 1,  title: 'Monthly Salary',    category: 'Salary',      categoryColor: '#63d478', icon: 'Briefcase',    amount: 45000, type: 'income'  as const, date: 'Jun 1'  },
    { id: 2,  title: 'Freelance Project', category: 'Freelance',   categoryColor: '#38e8a0', icon: 'Monitor',      amount: 12000, type: 'income'  as const, date: 'Jun 3'  },
    { id: 3,  title: 'Grocery Store',     category: 'Food',        categoryColor: '#f87171', icon: 'Utensils',     amount: 1450,  type: 'expense' as const, date: 'Jun 4'  },
    { id: 4,  title: 'Electricity Bill',  category: 'Utilities',   categoryColor: '#60b4ff', icon: 'Lightbulb',    amount: 980,   type: 'expense' as const, date: 'Jun 6'  },
    { id: 5,  title: 'Online Sales',      category: 'Freelance',   categoryColor: '#38e8a0', icon: 'Package',      amount: 3200,  type: 'income'  as const, date: 'Jun 8'  },
    { id: 6,  title: 'Grab Ride',         category: 'Transport',   categoryColor: '#fb923c', icon: 'Car',          amount: 280,   type: 'expense' as const, date: 'Jun 5'  },
    { id: 7,  title: 'Stock Dividend',    category: 'Investments', categoryColor: '#facc15', icon: 'TrendingUp',   amount: 1800,  type: 'income'  as const, date: 'Jun 10' },
];

const chartMonths = [
    { label: 'Jan', income: 42000, expense: 28000 },
    { label: 'Feb', income: 38000, expense: 31000 },
    { label: 'Mar', income: 55000, expense: 29000 },
    { label: 'Apr', income: 47000, expense: 35000 },
    { label: 'May', income: 51000, expense: 32000 },
    { label: 'Jun', income: 62000, expense: 36000 },
];

const topCategories = [
    { name: 'Food',      icon: 'Utensils',     color: '#f87171', amount: 3890, pct: 38 },
    { name: 'Shopping',  icon: 'ShoppingCart', color: '#c084fc', amount: 2350, pct: 23 },
    { name: 'Utilities', icon: 'Lightbulb',    color: '#60b4ff', amount: 1980, pct: 19 },
    { name: 'Transport', icon: 'Car',          color: '#fb923c', amount: 1240, pct: 12 },
    { name: 'Health',    icon: 'Hospital',     color: '#f472b6', amount:  820, pct:  8 },
];

const totalIncome  = 62000;
const totalExpense = 36000;
const savingsRate  = Math.round(((totalIncome - totalExpense) / totalIncome) * 100);
const netBalance   = totalIncome - totalExpense;
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 font-display">

            <DashboardGreetings :savings-rate="savingsRate" />

            <DashboardStatsCards
                :total-income="totalIncome"
                :total-expense="totalExpense"
                :net-balance="netBalance"
            />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2">
                    <DashboardCashFlowChart :chart-months="chartMonths" />
                </div>
                <DashboardTopSpending :categories="topCategories" />
            </div>

            <DashboardRecentTransactions :transactions="recentTransactions" />

        </div>
    </AppLayout>
</template>