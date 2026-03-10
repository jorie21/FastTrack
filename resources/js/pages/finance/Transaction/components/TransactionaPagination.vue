<script setup lang="ts">
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

defineProps<{
    currentPage: number;
    totalPages: number;
    pageSize: number;
    totalItems: number;
    pageNumbers: (number | string)[];
}>();

const emit = defineEmits<{
    'update:current-page': [page: number];
}>();
</script>

<template>
    <div class="mt-2 flex flex-col items-center justify-between gap-4 sm:flex-row">
        <p class="font-mono text-[11px] text-white/25">
            Showing {{ (currentPage - 1) * pageSize + 1 }}–{{ Math.min(currentPage * pageSize, totalItems) }} of {{ totalItems }}
        </p>

        <div class="flex items-center gap-1.5">
            <!-- Prev -->
            <button
                @click="emit('update:current-page', currentPage - 1)"
                :disabled="currentPage === 1"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/8 bg-white/5 text-white/40 transition-all duration-200 enabled:hover:border-emerald-500/40 enabled:hover:bg-emerald-500/10 enabled:hover:text-emerald-400 disabled:opacity-30"
            >
                <ChevronLeft class="h-4 w-4" />
            </button>

            <!-- Pages -->
            <div class="flex items-center gap-1">
                <button
                    v-for="p in pageNumbers"
                    :key="p"
                    @click="typeof p === 'number' && emit('update:current-page', p)"
                    class="h-8 min-w-[32px] rounded-lg border font-mono text-[11px] font-bold transition-all duration-200"
                    :class="p === currentPage
                        ? 'border-emerald-500/40 bg-emerald-500/15 text-emerald-400'
                        : typeof p === 'number'
                            ? 'border-white/8 bg-white/5 text-white/35 hover:border-white/20 hover:text-white/60'
                            : 'border-transparent text-white/20 cursor-default'"
                >
                    {{ p }}
                </button>
            </div>

            <!-- Next -->
            <button
                @click="emit('update:current-page', currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/8 bg-white/5 text-white/40 transition-all duration-200 enabled:hover:border-emerald-500/40 enabled:hover:bg-emerald-500/10 enabled:hover:text-emerald-400 disabled:opacity-30"
            >
                <ChevronRight class="h-4 w-4" />
            </button>
        </div>
    </div>
</template>
