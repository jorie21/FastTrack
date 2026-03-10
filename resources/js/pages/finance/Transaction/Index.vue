<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
import { useCategoryState } from '@/composables/useCategoryState';
import { useTransactionState } from '@/composables/useTransactionState';
import AppLayout from '@/layouts/AppLayout.vue';


import type { BreadcrumbItem } from '@/types';


import AddCategoryModal from './components/AddCategoryModal.vue';
import AddTransactionModal   from './components/AddTransactionModal.vue';
import CategoryList          from './components/CategoryList.vue';
import TransactionPagination from './components/TransactionaPagination.vue';
import TransactionList       from './components/TransactionList.vue';
import TransactionTabs       from './components/TransactionTabs.vue';
import TransactionToolbar    from './components/TransactionToolBar.vue';

const { 
    categories, 
    fetchCategories, 
} = useCategoryState();

const {
    transactions,
    fetchTransactions,
    openModal: openTransactionModal,
} = useTransactionState();

onMounted(() => {
    fetchCategories();
    fetchTransactions();
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Transaction', href: 'transaction' },
];

// ── Tabs ──────────────────────────────────────────────────────────
const activeTab = ref<'transactions' | 'categories'>('transactions');

// ── Search & filter ───────────────────────────────────────────────
const search     = ref('');
const filterType = ref<'all' | 'income' | 'expense'>('all');
const filterCat  = ref('all');
const showFilter = ref(false);

const filteredTransactions = computed(() =>
    transactions.value.filter(t => {
        const title = t.title || (t as any).description || '';
        const matchSearch = title.toLowerCase().includes(search.value.toLowerCase());
        const matchType = filterType.value === 'all' || t.type === filterType.value;
        
        // Category filter (by name)
        let matchCat = filterCat.value === 'all';
        if (!matchCat) {
            const cat = categories.value.find(c => c.name === filterCat.value);
            matchCat = cat ? (t as any).category_id === cat.id : false;
        }

        return matchSearch && matchType && matchCat;
    })
);

// ── Pagination ────────────────────────────────────────────────────
const PAGE_SIZE   = 7;
const currentPage = ref(1);

watch([search, filterType, filterCat], () => { currentPage.value = 1; });

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredTransactions.value.length / PAGE_SIZE))
);

const paginatedTransactions = computed(() => {
    const start = (currentPage.value - 1) * PAGE_SIZE;
    return filteredTransactions.value.slice(start, start + PAGE_SIZE);
});

const pageNumbers = computed(() => {
    const total = totalPages.value;
    const cur   = currentPage.value;
    if (total <= 5) return Array.from({ length: total }, (_, i) => i + 1);
    if (cur <= 3)   return [1, 2, 3, 4, '...', total];
    if (cur >= total - 2) return [1, '...', total - 3, total - 2, total - 1, total];
    return [1, '...', cur - 1, cur, cur + 1, '...', total];
});

// ── Modal Visibility ──────────────────────────────────────────────
const showAddCat = ref(false);

</script>

<template>
    <Head title="Transactions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 font-display">

            <!-- Tabs -->
            <TransactionTabs
                :active-tab="activeTab"
                @update:active-tab="activeTab = $event"
            />

            <!-- ── Transactions tab ───────────────────────────── -->
            <div v-if="activeTab === 'transactions'" class="flex flex-col gap-4">

                <TransactionToolbar
                    :search="search"
                    :filter-type="filterType"
                    :filter-cat="filterCat"
                    :show-filter="showFilter"
                    :categories="categories"
                    @update:search="search = $event"
                    @update:filter-type="filterType = $event"
                    @update:filter-cat="filterCat = $event"
                    @update:show-filter="showFilter = $event"
                    @add="openTransactionModal"
                />

                <!-- Results meta -->
                <div class="flex items-center justify-between">
                    <p class="font-mono text-[11px] uppercase tracking-widest text-white/30">
                        {{ filteredTransactions.length }} result{{ filteredTransactions.length !== 1 ? 's' : '' }}
                    </p>
                    <p class="font-mono text-[11px] text-white/25">
                        Page {{ currentPage }} of {{ totalPages }}
                    </p>
                </div>

                <TransactionList
                    :transactions="paginatedTransactions"
                    :categories="categories"
                />

                <TransactionPagination
                    :current-page="currentPage"
                    :total-pages="totalPages"
                    :page-size="PAGE_SIZE"
                    :total-items="filteredTransactions.length"
                    :page-numbers="pageNumbers"
                    @update:current-page="currentPage = $event"
                />
            </div>

            <!-- ── Categories tab ────────────────────────────── -->
            <CategoryList
                v-if="activeTab === 'categories'"
                @add="showAddCat = true"
            />

        </div>

        <!-- Modals -->
        <AddTransactionModal
            :categories="categories"
        />

        <AddCategoryModal
            :open="showAddCat"
            @close="showAddCat = false"
        />

    </AppLayout>
</template>