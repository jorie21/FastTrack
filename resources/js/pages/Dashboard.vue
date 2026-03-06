<script setup lang="ts">

import { Head } from '@inertiajs/vue3';
import {
    TrendingUp, TrendingDown, Wallet, ArrowUpRight,
    ArrowDownLeft, ArrowRight, Zap,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
];

// ── Mock data ────────────────────────────────────────────────────
interface Transaction {
    id: number;
    title: string;
    category: string;
    categoryColor: string;
    icon: string;
    amount: number;
    type: 'income' | 'expense';
    date: string;
}

const recentTransactions: Transaction[] = [
    { id: 1,  title: 'Monthly Salary',    category: 'Salary',      categoryColor: '#63d478', icon: '💼', amount: 45000, type: 'income',  date: 'Jun 1'  },
    { id: 2,  title: 'Freelance Project', category: 'Freelance',   categoryColor: '#38e8a0', icon: '💻', amount: 12000, type: 'income',  date: 'Jun 3'  },
    { id: 3,  title: 'Grocery Store',     category: 'Food',        categoryColor: '#f87171', icon: '🍔', amount: 1450,  type: 'expense', date: 'Jun 4'  },
    { id: 4,  title: 'Electricity Bill',  category: 'Utilities',   categoryColor: '#60b4ff', icon: '💡', amount: 980,   type: 'expense', date: 'Jun 6'  },
    { id: 5,  title: 'Online Sales',      category: 'Freelance',   categoryColor: '#38e8a0', icon: '📦', amount: 3200,  type: 'income',  date: 'Jun 8'  },
    { id: 6,  title: 'Grab Ride',         category: 'Transport',   categoryColor: '#fb923c', icon: '🚗', amount: 280,   type: 'expense', date: 'Jun 5'  },
    { id: 7,  title: 'Stock Dividend',    category: 'Investments', categoryColor: '#facc15', icon: '📈', amount: 1800,  type: 'income',  date: 'Jun 10' },
];

// Chart bars (last 6 months mock)
const chartMonths = [
    { label: 'Jan', income: 42000, expense: 28000 },
    { label: 'Feb', income: 38000, expense: 31000 },
    { label: 'Mar', income: 55000, expense: 29000 },
    { label: 'Apr', income: 47000, expense: 35000 },
    { label: 'May', income: 51000, expense: 32000 },
    { label: 'Jun', income: 62000, expense: 36000 },
];

const maxVal = Math.max(...chartMonths.flatMap(m => [m.income, m.expense]));
const barH = (val: number, maxPx = 120) => Math.round((val / maxVal) * maxPx);

// Top spending categories
const topCategories = [
    { name: 'Food',      icon: '🍔', color: '#f87171', amount: 3890, pct: 38 },
    { name: 'Shopping',  icon: '🛒', color: '#c084fc', amount: 2350, pct: 23 },
    { name: 'Utilities', icon: '💡', color: '#60b4ff', amount: 1980, pct: 19 },
    { name: 'Transport', icon: '🚗', color: '#fb923c', amount: 1240, pct: 12 },
    { name: 'Health',    icon: '🏥', color: '#f472b6', amount:  820, pct:  8 },
];

const totalIncome  = 62000;
const totalExpense = 36000;
const netBalance   = totalIncome - totalExpense;
const savingsRate  = Math.round(((totalIncome - totalExpense) / totalIncome) * 100);

const fmt = (n: number) => '₱' + n.toLocaleString();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 font-display">

            <!-- ── Greeting ──────────────────────────────────────── -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white">
                        Good morning! ⚡
                    </h1>
                    <p class="font-mono text-xs text-white/35 mt-1 uppercase tracking-widest">
                        June 2025 · Financial Overview
                    </p>
                </div>
                <div class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl border border-emerald-500/20 bg-emerald-500/8"
                    style="background: rgba(99,212,120,0.06);">
                    <Zap class="w-3.5 h-3.5 text-emerald-400" />
                    <span class="font-mono text-xs font-semibold text-emerald-400">
                        Savings rate: {{ savingsRate }}%
                    </span>
                </div>
            </div>

            <!-- ── Stat cards ────────────────────────────────────── -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <!-- Income -->
                <div class="rounded-2xl border border-white/8 p-5 flex items-center gap-4 transition-all duration-200 hover:border-emerald-500/20"
                    style="background: rgba(99,212,120,0.05); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-emerald-500/12">
                        <TrendingUp class="w-5 h-5 text-emerald-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-mono text-[11px] uppercase tracking-widest text-white/35 mb-1">Total Income</p>
                        <p class="font-display font-extrabold text-2xl tracking-tight text-emerald-400">{{ fmt(totalIncome) }}</p>
                        <p class="font-mono text-[10px] text-emerald-400/50 mt-0.5">+12% vs last month</p>
                    </div>
                </div>

                <!-- Expense -->
                <div class="rounded-2xl border border-white/8 p-5 flex items-center gap-4 transition-all duration-200 hover:border-red-500/20"
                    style="background: rgba(248,113,113,0.05); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-red-500/12">
                        <TrendingDown class="w-5 h-5 text-red-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-mono text-[11px] uppercase tracking-widest text-white/35 mb-1">Total Expenses</p>
                        <p class="font-display font-extrabold text-2xl tracking-tight text-red-400">{{ fmt(totalExpense) }}</p>
                        <p class="font-mono text-[10px] text-red-400/50 mt-0.5">+4% vs last month</p>
                    </div>
                </div>

                <!-- Net Balance -->
                <div class="rounded-2xl border border-white/8 p-5 flex items-center gap-4 transition-all duration-200 hover:border-blue-500/20"
                    style="background: rgba(96,180,255,0.05); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-blue-500/12">
                        <Wallet class="w-5 h-5 text-blue-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-mono text-[11px] uppercase tracking-widest text-white/35 mb-1">Net Balance</p>
                        <p class="font-display font-extrabold text-2xl tracking-tight text-blue-400">{{ fmt(netBalance) }}</p>
                        <p class="font-mono text-[10px] text-blue-400/50 mt-0.5">Saved this month</p>
                    </div>
                </div>
            </div>

            <!-- ── Chart + Top Categories ────────────────────────── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <!-- Cash Flow Chart -->
                <div class="lg:col-span-2 rounded-2xl border border-white/8 p-6"
                    style="background: rgba(255,255,255,0.03); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-base font-extrabold tracking-tight text-white">Cash Flow</h2>
                            <p class="font-mono text-[11px] text-white/30 mt-0.5 uppercase tracking-widest">Last 6 months</p>
                        </div>
                        <div class="flex items-center gap-4 font-mono text-[11px] text-white/40">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-sm bg-emerald-400 inline-block" />
                                Income
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-sm bg-red-400 inline-block" />
                                Expense
                            </span>
                        </div>
                    </div>

                    <!-- Bar chart -->
                    <div class="flex items-end justify-between gap-3 h-36 px-1">
                        <div v-for="(m, i) in chartMonths" :key="i"
                            class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full flex items-end justify-center gap-1"
                                :style="{ height: '120px' }">
                                <!-- Income bar -->
                                <div
                                    class="flex-1 rounded-t-md animate-grow-up"
                                    :style="{
                                        height: barH(m.income) + 'px',
                                        background: 'linear-gradient(to top, #3db85a, #63d478)',
                                        animationDelay: (i * 0.08) + 's',
                                    }"
                                />
                                <!-- Expense bar -->
                                <div
                                    class="flex-1 rounded-t-md animate-grow-up"
                                    :style="{
                                        height: barH(m.expense) + 'px',
                                        background: 'linear-gradient(to top, #b91c1c, #f87171)',
                                        animationDelay: (i * 0.08 + 0.04) + 's',
                                    }"
                                />
                            </div>
                            <span class="font-mono text-[10px] text-white/30">{{ m.label }}</span>
                        </div>
                    </div>
                </div>

                <!-- Top Spending Categories -->
                <div class="rounded-2xl border border-white/8 p-6"
                    style="background: rgba(255,255,255,0.03); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);">
                    <div class="mb-5">
                        <h2 class="text-base font-extrabold tracking-tight text-white">Top Spending</h2>
                        <p class="font-mono text-[11px] text-white/30 mt-0.5 uppercase tracking-widest">By category</p>
                    </div>
                    <div class="flex flex-col gap-3.5">
                        <div v-for="cat in topCategories" :key="cat.name" class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-base leading-none">{{ cat.icon }}</span>
                                    <span class="text-sm font-semibold text-white/75">{{ cat.name }}</span>
                                </div>
                                <span class="font-mono text-xs font-semibold" :style="{ color: cat.color }">
                                    {{ fmt(cat.amount) }}
                                </span>
                            </div>
                            <!-- Progress bar -->
                            <div class="w-full h-1.5 rounded-full bg-white/6">
                                <div
                                    class="h-1.5 rounded-full transition-all duration-700"
                                    :style="{ width: cat.pct + '%', background: cat.color }"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Recent Transactions ───────────────────────────── -->
            <div class="rounded-2xl border border-white/8 p-6"
                style="background: rgba(255,255,255,0.03); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);">

                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-base font-extrabold tracking-tight text-white">Recent Transactions</h2>
                        <p class="font-mono text-[11px] text-white/30 mt-0.5 uppercase tracking-widest">Latest activity</p>
                    </div>
                    <a href="/transaction"
                        class="flex items-center gap-1.5 font-mono text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors duration-200">
                        View all <ArrowRight class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="flex flex-col gap-2">
                    <div v-for="txn in recentTransactions" :key="txn.id"
                        class="group flex items-center gap-4 px-4 py-3.5 rounded-xl border border-white/5 hover:border-white/10 transition-all duration-200"
                        style="background: rgba(255,255,255,0.02);">

                        <!-- Icon -->
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-base shrink-0"
                            :style="{ background: txn.categoryColor + '18' }">
                            {{ txn.icon }}
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white/85 truncate">{{ txn.title }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="font-mono text-[10px] px-1.5 py-0.5 rounded-full"
                                    :style="{ color: txn.categoryColor, background: txn.categoryColor + '15' }">
                                    {{ txn.category }}
                                </span>
                                <span class="font-mono text-[10px] text-white/25">{{ txn.date }}</span>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            <component :is="txn.type === 'income' ? ArrowUpRight : ArrowDownLeft"
                                class="w-3.5 h-3.5"
                                :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'" />
                            <span class="font-mono font-semibold text-sm"
                                :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'">
                                {{ txn.type === 'income' ? '+' : '−' }}{{ fmt(txn.amount) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>