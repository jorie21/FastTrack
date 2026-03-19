<script setup lang="ts">
import { TrendingUp, TrendingDown, Activity, Landmark } from 'lucide-vue-next';
import { computed, onMounted } from 'vue';
import { useDashboardState } from '@/composables/useDashboardState';
import { useWalletState } from '@/composables/useWalletState';

const { todayStats, fetchTodayStats, isLoading: statsLoading } = useDashboardState();
const { wallets, isLoading: walletsLoading } = useWalletState();

onMounted(() => {
    fetchTodayStats();
});

const totalBalance = computed(() => {
    return wallets.value.reduce((acc, w) => acc + Number(w.balance), 0);
});

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
    }).format(amount);
};

const analyticsCards = computed(() => [
    {
        title: "Today's Income",
        value: formatCurrency(todayStats.value.income),
        sub: 'Added today',
        icon: TrendingUp,
        color: 'text-emerald-400',
        bg: 'bg-emerald-500/10',
        border: 'border-emerald-500/20'
    },
    {
        title: "Today's Expenses",
        value: formatCurrency(todayStats.value.expense),
        sub: 'Spent today',
        icon: TrendingDown,
        color: 'text-red-400',
        bg: 'bg-red-500/10',
        border: 'border-red-500/20'
    },
    {
        title: "Daily Net Movement",
        value: formatCurrency(todayStats.value.net),
        sub: 'Change in 24h',
        icon: Activity,
        color: todayStats.value.net >= 0 ? 'text-blue-400' : 'text-orange-400',
        bg: todayStats.value.net >= 0 ? 'bg-blue-500/10' : 'bg-orange-500/10',
        border: todayStats.value.net >= 0 ? 'border-blue-500/20' : 'border-orange-500/20'
    }
]);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div 
            v-for="card in analyticsCards" 
            :key="card.title"
            class="group relative overflow-hidden rounded-2xl border p-5 transition-all duration-300 hover:bg-white/5"
            :class="[card.border, 'bg-white/[0.03]']"
        >
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-[10px] uppercase tracking-widest text-white/35">{{ card.title }}</p>
                    <h3 class="mt-1 font-display text-xl font-black tracking-tight text-white sm:text-2xl">
                        {{ card.value }}
                    </h3>
                    <p class="mt-1 font-mono text-[10px] text-white/20">{{ card.sub }}</p>
                </div>
                <div 
                    class="flex h-10 w-10 items-center justify-center rounded-xl"
                    :class="[card.bg, card.color]"
                >
                    <component :is="card.icon" class="h-5 w-5" />
                </div>
            </div>

            <!-- Subtle background decorative pattern -->
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] transition-transform duration-500 group-hover:scale-110">
                <component :is="card.icon" class="h-20 w-20" />
            </div>
        </div>
    </div>
</template>
