<script setup lang="ts">
import { onMounted } from 'vue';
import { useDashboardState } from '@/composables/useDashboardState';
import DynamicIcon from '@/components/DynamicIcon.vue';

const { topSpending, fetchTopSpending } = useDashboardState();

onMounted(() => {
    fetchTopSpending();
});

const fmt = (n: number) => '₱' + n.toLocaleString();
</script>

<template>
    <div
        class="rounded-2xl border border-white/8 p-6"
        style="background: rgba(255,255,255,0.03); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);"
    >
        <div class="mb-5">
            <h2 class="text-base font-extrabold tracking-tight text-white">Top Spending</h2>
            <p class="font-mono text-[11px] text-white/30 mt-0.5 uppercase tracking-widest">This month</p>
        </div>

        <div class="flex flex-col gap-3.5">
            <div v-if="topSpending.length > 0" v-for="cat in topSpending" :key="cat.name" class="flex flex-col gap-1.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <DynamicIcon :name="cat.icon" class="w-4 h-4" :style="{ color: cat.color }" />
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
            <div v-else class="py-8 text-center">
                <p class="font-mono text-[10px] text-white/20 uppercase tracking-widest">No expenses yet</p>
            </div>
        </div>
    </div>
</template>
