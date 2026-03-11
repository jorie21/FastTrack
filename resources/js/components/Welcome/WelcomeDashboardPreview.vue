<script setup lang="ts">
import { ArrowUpRight, ArrowDownLeft, TrendingUp, CheckCircle2 } from 'lucide-vue-next';
import DynamicIcon from '@/components/DynamicIcon.vue';

const recentTransactions = [
    { icon: 'Briefcase',    name: 'Freelance Project',  cat: 'Income',      amount: '+₱12,000', type: 'income'  },
    { icon: 'ShoppingCart', name: 'Grocery Store',      cat: 'Food & Home', amount: '−₱1,450',  type: 'expense' },
    { icon: 'Lightbulb',    name: 'Electricity Bill',   cat: 'Utilities',   amount: '−₱980',    type: 'expense' },
    { icon: 'Package',      name: 'Online Sales',       cat: 'Income',      amount: '+₱3,200',  type: 'income'  },
];
</script>

<template>
    <div class="relative group">
        <!-- Floating "Glass" Card -->
        <div class="relative z-10 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-2xl shadow-2xl transition-all duration-500 group-hover:border-white/20">
            
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold tracking-tight text-white">Recent Activity</h3>
                    <p class="font-mono text-[10px] uppercase tracking-widest text-white/30">Live monitoring</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10">
                    <TrendingUp class="h-5 w-5 text-emerald-400" />
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <div
                    v-for="txn in recentTransactions"
                    :key="txn.name"
                    class="flex items-center gap-4 rounded-2xl border border-white/5 bg-white/2 p-4 transition-all duration-300 hover:bg-white/5"
                >
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/5 text-base">
                        <DynamicIcon :name="txn.icon" class="w-5 h-5 text-white/40" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-bold text-white/90">{{ txn.name }}</p>
                        <p class="font-mono text-[10px] text-white/25 uppercase">{{ txn.cat }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <div class="flex items-center gap-1.5">
                            <component
                                :is="txn.type === 'income' ? ArrowUpRight : ArrowDownLeft"
                                class="h-3 w-3"
                                :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'"
                            />
                            <span
                                class="font-mono text-sm font-bold"
                                :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'"
                            >
                                {{ txn.amount }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Overlay -->
            <div class="mt-6 grid grid-cols-1 xs:grid-cols-2 gap-3">
                <div class="rounded-2xl bg-emerald-500/10 p-4 border border-emerald-500/10">
                    <div class="flex items-center gap-2 text-emerald-400 mb-1">
                        <TrendingUp class="w-3 h-3" />
                        <span class="font-mono text-[10px] font-bold uppercase tracking-tighter">+18% vs last month</span>
                    </div>
                    <p class="text-xs text-white/40 font-medium leading-tight">Growth on track</p>
                </div>
                <div class="rounded-2xl bg-blue-500/10 p-4 border border-blue-500/10">
                    <div class="flex items-center gap-2 text-blue-400 mb-1">
                        <CheckCircle2 class="w-3 h-3" />
                        <span class="font-mono text-[10px] font-bold uppercase tracking-tighter">Budget on track</span>
                    </div>
                    <p class="text-xs text-white/40 font-medium leading-tight">Safe spending</p>
                </div>
            </div>
        </div>

        <!-- Decorative background glow -->
        <div class="absolute -inset-4 z-0 bg-emerald-500/10 blur-3xl rounded-[40px] opacity-0 group-hover:opacity-100 transition-opacity duration-700" />
    </div>
</template>
