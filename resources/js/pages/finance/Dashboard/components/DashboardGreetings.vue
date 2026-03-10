<script setup lang="ts">
import { Zap } from 'lucide-vue-next';

defineProps<{
    savingsRate: number;
}>();
</script>

<template>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white flex items-center gap-2">
                Good morning! <Zap class="w-6 h-6 text-emerald-400 fill-current" />
            </h1>
            <p class="font-mono text-xs text-white/35 mt-1 uppercase tracking-widest">
                June 2025 · Financial Overview
            </p>
        </div>
        <div
            class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl border border-emerald-500/20 bg-emerald-500/8"
            style="background: rgba(99,212,120,0.06);"
        >
            <Zap class="w-3.5 h-3.5 text-emerald-400" />
            <span class="font-mono text-xs font-semibold text-emerald-400">
                Savings rate: {{ savingsRate }}%
            </span>
        </div>
    </div>
</template>
