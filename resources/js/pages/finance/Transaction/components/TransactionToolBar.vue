<script setup lang="ts">
import { Search, SlidersHorizontal, Plus, ChevronDown, Download } from 'lucide-vue-next';
import type { Category } from '../types';
defineProps<{
    search: string;
    filterType: 'all' | 'income' | 'expense';
    filterCat: string;
    showFilter: boolean;
    categories: Category[];
}>();

const emit = defineEmits<{
    'update:search':     [value: string];
    'update:filterType': [value: 'all' | 'income' | 'expense'];
    'update:filterCat':  [value: string];
    'update:showFilter': [value: boolean];
    'add': [];
    'export': [];
}>();
</script>

<template>
    <div class="flex flex-col gap-3">

        <!-- Row: search + filter toggle + add button -->
        <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <Search class="absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-white/30" />
                <input
                    :value="search"
                    @input="emit('update:search', ($event.target as HTMLInputElement).value)"
                    type="text"
                    placeholder="Search transactions…"
                    class="w-full rounded-xl border border-white/10 bg-white/5 py-2.5 pr-4 pl-10 font-mono text-sm text-white/80 transition-all duration-200 placeholder:text-white/25 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none"
                />
            </div>

            <button
                @click="emit('update:showFilter', !showFilter)"
                class="flex items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-semibold transition-all duration-200"
                :class="showFilter
                    ? 'border-emerald-500/50 bg-emerald-500/10 text-emerald-400'
                    : 'border-white/10 bg-white/5 text-white/60 hover:bg-white/8 hover:text-white'"
            >
                <SlidersHorizontal class="h-4 w-4" />
                Filters
            </button>

            <div class="flex items-center gap-2">
                <button
                    @click="emit('export')"
                    class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white/60 transition-all duration-200 hover:bg-white/8 hover:text-white"
                >
                    <Download class="h-4 w-4" />
                    Export
                </button>

                <button
                    @click="emit('add')"
                    class="flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold text-[#0b0f1a] transition-all duration-200 hover:-translate-y-px hover:shadow-lg hover:shadow-emerald-500/25"
                    style="background: linear-gradient(135deg, #63d478, #38b858);"
                >
                    <Plus class="h-4 w-4" />
                    Add Transaction
                </button>
            </div>
        </div>

        <!-- Filter panel -->
        <div
            v-if="showFilter"
            class="flex animate-fade-in-up flex-wrap gap-4 rounded-xl border border-white/8 p-4"
            style="background: rgba(255,255,255,0.03);"
        >
            <!-- Type -->
            <div class="flex flex-col gap-1.5">
                <span class="font-mono text-[10px] uppercase tracking-widest text-white/35">Type</span>
                <div class="flex gap-2">
                    <button
                        v-for="t in ['all', 'income', 'expense']"
                        :key="t"
                        @click="emit('update:filterType', t as any)"
                        class="rounded-lg border px-3 py-1.5 text-xs font-semibold capitalize transition-all duration-200"
                        :class="filterType === t
                            ? t === 'income'  ? 'border-emerald-500/40 bg-emerald-500/15 text-emerald-400'
                            : t === 'expense' ? 'border-red-500/40 bg-red-500/15 text-red-400'
                            : 'border-white/20 bg-white/8 text-white/80'
                            : 'border-white/8 bg-transparent text-white/40 hover:text-white/60'"
                    >{{ t }}</button>
                </div>
            </div>

            <!-- Category -->
            <div class="flex flex-col gap-1.5">
                <span class="font-mono text-[10px] uppercase tracking-widest text-white/35">Category</span>
                <div class="relative">
                    <select
                        :value="filterCat"
                        @change="emit('update:filterCat', ($event.target as HTMLSelectElement).value)"
                        class="cursor-pointer appearance-none rounded-lg border border-white/10 bg-white/5 py-1.5 pr-8 pl-3 font-mono text-xs text-white/70 focus:border-emerald-500/40 focus:outline-none"
                    >
                        <option value="all" class="bg-[#0d1117]">All categories</option>
                        <option v-for="c in categories" :key="c.id" :value="String(c.id)" class="bg-[#0d1117]">
                            {{ c.icon }} {{ c.name }}
                        </option>
                    </select>
                    <ChevronDown class="pointer-events-none absolute top-1/2 right-2 h-3 w-3 -translate-y-1/2 text-white/30" />
                </div>
            </div>
        </div>
    </div>
</template>