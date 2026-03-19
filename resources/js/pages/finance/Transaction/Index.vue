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


const activeTab = ref<'transactions' | 'categories'>('transactions');

const search     = ref('');
const filterType = ref<'all' | 'income' | 'expense'>('all');
const filterCat  = ref('all');
const showFilter = ref(false);

const updateFilters = () => {
    const filters: Record<string, any> = {};
    if (search.value) filters.keyword = search.value;
    if (filterType.value !== 'all') filters.type = filterType.value;
    if (filterCat.value !== 'all') filters.category_id = filterCat.value;
    
    fetchTransactions(filters);
};

// Debounce search
let searchTimeout: any;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(updateFilters, 300);
});

watch([filterType, filterCat], updateFilters);

const filteredTransactions = computed(() => transactions.value);

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

// ── Export ────────────────────────────────────────────────────────
const handleExport = () => {
    if (transactions.value.length === 0) return;

    const headers = ['Date', 'Description', 'Category', 'Type', 'Amount'];
    const rows = filteredTransactions.value.map(t => {
        const catName = categories.value.find(c => c.id === (t as any).category_id)?.name || 'General';
        return [
            t.date || (t as any).transaction_date,
            t.title || (t as any).description,
            catName,
            t.type,
            t.amount
        ];
    });

    const csvContent = [
        headers.join(','),
        ...rows.map(r => r.map(val => `"${val}"`).join(','))
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `transactions_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

</script>

<template>
    <Head title="Transactions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 font-display">

            <TransactionTabs
                :active-tab="activeTab"
                @update:active-tab="activeTab = $event"
            />

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
                    @export="handleExport"
                />

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

            <CategoryList
                v-if="activeTab === 'categories'"
                @add="showAddCat = true"
            />

        </div>

        <AddTransactionModal
            :categories="categories"
        />

        <AddCategoryModal
            :open="showAddCat"
            @close="showAddCat = false"
        />

    </AppLayout>
</template>