<script setup lang="ts">
import { ArrowUpRight, ArrowDownLeft, Trash2, Pencil, Search } from 'lucide-vue-next';
import { onMounted } from 'vue';
import DynamicIcon from '@/components/DynamicIcon.vue';
import { useTransactionState } from '@/composables/useTransactionState';
import type { Category } from '../types';

defineProps<{
    categories: Category[];
}>();

const { 
    transactions, 
    fetchTransactions, 
    deleteTransaction, 
    editTransaction 
} = useTransactionState();

onMounted(() => {
    fetchTransactions();
});

const fmt = (n: number) => '₱' + Number(n).toLocaleString();

const getCategory = (id: number, categories: Category[]) => {
    return categories.find(c => c.id === id);
};
</script>

<template>
    <!-- List -->
    <div v-if="transactions.length > 0" class="flex flex-col gap-2.5">
        <div
            v-for="txn in transactions"
            :key="txn.uuid"
            @click="editTransaction(txn)"
            class="group flex cursor-pointer items-center gap-4 rounded-2xl border border-white/6 px-5 py-4 transition-all duration-200 hover:border-white/12"
            style="background: rgba(255,255,255,0.03);"
        >
            <!-- Icon -->
            <div
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-lg"
                :style="{ background: (getCategory((txn as any).category_id, categories)?.color ?? '#63d478') + '18' }"
            >
                <DynamicIcon 
                    :name="getCategory((txn as any).category_id, categories)?.icon ?? 'CreditCard'" 
                    class="w-5 h-5" 
                    :style="{ color: getCategory((txn as any).category_id, categories)?.color ?? '#63d478' }"
                />
            </div>

            <!-- Info -->
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-white/90">{{ txn.title || (txn as any).description }}</p>
                <div class="mt-0.5 flex items-center gap-2">
                    <span
                        class="rounded-full px-2 py-0.5 font-mono text-[10px]"
                        :style="{ 
                            color: getCategory((txn as any).category_id, categories)?.color ?? '#63d478', 
                            background: (getCategory((txn as any).category_id, categories)?.color ?? '#63d478') + '18' 
                        }"
                    >{{ getCategory((txn as any).category_id, categories)?.name ?? 'General' }}</span>
                    <span class="font-mono text-[10px] text-white/25">{{ txn.date || (txn as any).transaction_date }}</span>
                    <span v-if="txn.note" class="truncate font-mono text-[10px] text-white/25">· {{ txn.note }}</span>
                </div>
            </div>

            <!-- Amount + actions -->
            <div class="flex shrink-0 items-center gap-3">
                <div class="flex items-center gap-1.5">
                    <component
                        :is="txn.type === 'income' ? ArrowUpRight : ArrowDownLeft"
                        class="h-4 w-4"
                        :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'"
                    />
                    <span
                        class="font-mono text-base font-semibold"
                        :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'"
                    >
                        {{ txn.type === 'income' ? '+' : '−' }}{{ fmt(txn.amount) }}
                    </span>
                </div>
                
                <div class="flex items-center gap-1">
                    <button
                        @click.stop="editTransaction(txn)"
                        class="rounded-lg p-1.5 text-white/25 opacity-0 transition-all duration-200 group-hover:opacity-100 hover:bg-white/5 hover:text-white"
                    >
                        <Pencil class="h-3.5 w-3.5" />
                    </button>
                    <button
                        @click.stop="deleteTransaction(txn.uuid)"
                        class="rounded-lg p-1.5 text-white/25 opacity-0 transition-all duration-200 group-hover:opacity-100 hover:bg-red-500/10 hover:text-red-400"
                    >
                        <Trash2 class="h-3.5 w-3.5" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty state -->
    <div v-else class="flex flex-col items-center justify-center gap-3 py-16">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/8 bg-white/5">
            <Search class="w-8 h-8 text-white/20" />
        </div>
        <p class="font-display font-bold text-white/40">No transactions found</p>
        <p class="font-mono text-xs text-white/25">Try adjusting your search or filters</p>
    </div>
</template>
