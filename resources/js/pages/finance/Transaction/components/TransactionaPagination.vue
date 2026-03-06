<script setup lang="ts">
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationEllipsis,
} from '@/components/ui/pagination';

defineProps<{
    currentPage: number;
    totalPages: number;
    pageSize: number;
    totalItems: number;
    pageNumbers: (number | string)[];
}>();

const emit = defineEmits<{
    'update:currentPage': [value: number];
}>();

function goToPage(p: number | string) {
    if (typeof p === 'number') emit('update:currentPage', p);
}
</script>

<template>
    <div
        v-if="totalPages > 1"
        class="flex flex-col items-center justify-between gap-3 pt-2 sm:flex-row"
    >
        <!-- Info -->
        <p class="font-mono text-[11px] text-white/30">
            Showing {{ (currentPage - 1) * pageSize + 1 }}–{{ Math.min(currentPage * pageSize, totalItems) }}
            of {{ totalItems }}
        </p>

        <Pagination
            :total="totalItems"
            :items-per-page="pageSize"
            :sibling-count="1"
            show-edges
            :page="currentPage"
            @update:page="emit('update:currentPage', $event)"
        >
            <PaginationContent class="flex items-center gap-1">

                <!-- Prev -->
                <button
                    :disabled="currentPage === 1"
                    @click="goToPage(currentPage - 1)"
                    class="h-9 w-9 rounded-xl border border-white/10 bg-transparent text-white/50 transition-all duration-200 hover:border-white/20 hover:bg-white/5 hover:text-white disabled:opacity-30"
                >‹</button>

                <template v-for="(item, i) in pageNumbers" :key="i">
                    <PaginationItem v-if="typeof item === 'number'" :value="item">
                        <button
                            @click="goToPage(item)"
                            class="h-9 w-9 rounded-xl border text-sm font-bold transition-all duration-200"
                            :class="currentPage === item
                                ? 'border-transparent text-[#0b0f1a] shadow-lg shadow-emerald-500/20'
                                : 'border-white/8 bg-transparent text-white/45 hover:border-white/20 hover:text-white/80'"
                            :style="currentPage === item ? 'background: linear-gradient(135deg, #63d478, #38b858);' : ''"
                        >{{ item }}</button>
                    </PaginationItem>
                    <PaginationItem v-else :value="0" disabled>
                        <PaginationEllipsis class="text-white/25" />
                    </PaginationItem>
                </template>

                <!-- Next -->
                <button
                    :disabled="currentPage === totalPages"
                    @click="goToPage(currentPage + 1)"
                    class="h-9 w-9 rounded-xl border border-white/10 bg-transparent text-white/50 transition-all duration-200 hover:border-white/20 hover:bg-white/5 hover:text-white disabled:opacity-30"
                >›</button>

            </PaginationContent>
        </Pagination>
    </div>
</template>