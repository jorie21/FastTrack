<script setup lang="ts">
import { ArrowUpRight, ArrowDownLeft, ArrowRight } from 'lucide-vue-next';
import DynamicIcon from '@/components/DynamicIcon.vue';

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

defineProps<{
    transactions: Transaction[];
}>();

const fmt = (n: number) => '₱' + n.toLocaleString();
</script>

<template>
    <div
        class="rounded-2xl border border-white/8 p-6"
        style="background: rgba(255,255,255,0.03); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);"
    >
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-base font-extrabold tracking-tight text-white">Recent Transactions</h2>
                <p class="font-mono text-[11px] text-white/30 mt-0.5 uppercase tracking-widest">Latest activity</p>
            </div>
            <a
                href="/transaction"
                class="flex items-center gap-1.5 font-mono text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors duration-200"
            >
                View all <ArrowRight class="w-3.5 h-3.5" />
            </a>
        </div>

        <div class="flex flex-col gap-2">
            <div
                v-for="txn in transactions"
                :key="txn.id"
                class="group flex items-center gap-4 px-4 py-3.5 rounded-xl border border-white/5 hover:border-white/10 transition-all duration-200"
                style="background: rgba(255,255,255,0.02);"
            >
                <!-- Icon -->
                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center text-base shrink-0"
                    :style="{ background: txn.categoryColor + '18' }"
                >
                    <DynamicIcon :name="txn.icon" class="w-5 h-5" :style="{ color: txn.categoryColor }" />
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white/85 truncate">{{ txn.title }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span
                            class="font-mono text-[10px] px-1.5 py-0.5 rounded-full"
                            :style="{ color: txn.categoryColor, background: txn.categoryColor + '15' }"
                        >
                            {{ txn.category }}
                        </span>
                        <span class="font-mono text-[10px] text-white/25">{{ txn.date }}</span>
                    </div>
                </div>

                <!-- Amount -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <component
                        :is="txn.type === 'income' ? ArrowUpRight : ArrowDownLeft"
                        class="w-3.5 h-3.5"
                        :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'"
                    />
                    <span
                        class="font-mono font-semibold text-sm"
                        :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'"
                    >
                        {{ txn.type === 'income' ? '+' : '−' }}{{ fmt(txn.amount) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
