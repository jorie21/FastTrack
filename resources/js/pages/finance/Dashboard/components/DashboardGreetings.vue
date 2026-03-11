<script setup lang="ts">
import { Zap } from 'lucide-vue-next';
import { onMounted } from 'vue';
import { useDashboardState } from '@/composables/useDashboardState';

const { stats, fetchStats } = useDashboardState();

onMounted(() => {
    fetchStats();
});

const currentMonth = new Date().toLocaleString('default', { month: 'long', year: 'numeric' });
</script>

<template>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-extrabold tracking-tight text-white sm:text-2xl flex items-center gap-2">
                Good morning!
            </h1>
            <p class="font-mono text-[10px] sm:text-xs text-white/35 mt-1 uppercase tracking-widest">
                {{ currentMonth }} · Financial Overview
            </p>
        </div>
        <div
            class="flex items-center gap-2 px-4 py-2 rounded-xl border border-emerald-500/20 bg-emerald-500/8 self-start sm:self-auto"
            style="background: rgba(99,212,120,0.06);"
        >
            <Zap class="w-3.5 h-3.5 text-emerald-400" />
            <span class="font-mono text-xs font-semibold text-emerald-400">
                Savings rate: {{ stats.savings_rate }}%
            </span>
        </div>
    </div>
</template>
