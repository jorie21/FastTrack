<script setup lang="ts">
import { TrendingUp, TrendingDown, Wallet } from 'lucide-vue-next';

defineProps<{
    totalIncome: number;
    totalExpense: number;
    netBalance: number;
}>();

const fmt = (n: number) => '₱' + n.toLocaleString();
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <!-- Income -->
        <div
            class="rounded-2xl border border-white/8 p-5 flex items-center gap-4 transition-all duration-200 hover:border-emerald-500/20"
            style="background: rgba(99,212,120,0.05); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);"
        >
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
        <div
            class="rounded-2xl border border-white/8 p-5 flex items-center gap-4 transition-all duration-200 hover:border-red-500/20"
            style="background: rgba(248,113,113,0.05); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);"
        >
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
        <div
            class="rounded-2xl border border-white/8 p-5 flex items-center gap-4 transition-all duration-200 hover:border-blue-500/20"
            style="background: rgba(96,180,255,0.05); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);"
        >
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
</template>