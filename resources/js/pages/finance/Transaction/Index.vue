<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';


import type { BreadcrumbItem } from '@/types';
import type { Category, Transaction } from '../Transaction/types';

import AddCategoryModal from './components/AddCategoryModal.vue';
import AddTransactionModal   from './components/AddTransactionModal.vue';
import CategoryList          from './components/CategoryList.vue';
import TransactionPagination from './components/TransactionaPagination.vue';
import TransactionList       from './components/TransactionList.vue';
import TransactionTabs       from './components/TransactionTabs.vue';
import TransactionToolbar    from './components/TransactionToolBar.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Transaction', href: 'transaction' },
];

// ── Tabs ──────────────────────────────────────────────────────────
const activeTab = ref<'transactions' | 'categories'>('transactions');

// ── Categories ────────────────────────────────────────────────────
const categories = ref<Category[]>([
    { id: 1, name: 'Salary',      color: '#63d478', icon: '💼', type: 'income'  },
    { id: 2, name: 'Freelance',   color: '#38e8a0', icon: '💻', type: 'income'  },
    { id: 3, name: 'Food',        color: '#f87171', icon: '🍔', type: 'expense' },
    { id: 4, name: 'Transport',   color: '#fb923c', icon: '🚗', type: 'expense' },
    { id: 5, name: 'Utilities',   color: '#60b4ff', icon: '💡', type: 'expense' },
    { id: 6, name: 'Shopping',    color: '#c084fc', icon: '🛒', type: 'expense' },
    { id: 7, name: 'Health',      color: '#f472b6', icon: '🏥', type: 'expense' },
    { id: 8, name: 'Investments', color: '#facc15', icon: '📈', type: 'both'    },
]);

// ── Transactions ──────────────────────────────────────────────────
const transactions = ref<Transaction[]>([
    { id: 1,  title: 'Monthly Salary',    category: 'Salary',      categoryColor: '#63d478', amount: 45000, type: 'income',  date: '2025-06-01', note: 'June payroll'     },
    { id: 2,  title: 'Freelance Project', category: 'Freelance',   categoryColor: '#38e8a0', amount: 12000, type: 'income',  date: '2025-06-03'                           },
    { id: 3,  title: 'Grocery Store',     category: 'Food',        categoryColor: '#f87171', amount: 1450,  type: 'expense', date: '2025-06-04', note: 'Weekly groceries' },
    { id: 4,  title: 'Grab Ride',         category: 'Transport',   categoryColor: '#fb923c', amount: 280,   type: 'expense', date: '2025-06-05'                           },
    { id: 5,  title: 'Electricity Bill',  category: 'Utilities',   categoryColor: '#60b4ff', amount: 980,   type: 'expense', date: '2025-06-06'                           },
    { id: 6,  title: 'Online Shopping',   category: 'Shopping',    categoryColor: '#c084fc', amount: 2350,  type: 'expense', date: '2025-06-07', note: 'Shopee haul'      },
    { id: 7,  title: 'Online Sales',      category: 'Freelance',   categoryColor: '#38e8a0', amount: 3200,  type: 'income',  date: '2025-06-08'                           },
    { id: 8,  title: 'Pharmacy',          category: 'Health',      categoryColor: '#f472b6', amount: 650,   type: 'expense', date: '2025-06-09'                           },
    { id: 9,  title: 'Stock Dividend',    category: 'Investments', categoryColor: '#facc15', amount: 1800,  type: 'income',  date: '2025-06-10'                           },
    { id: 10, title: 'Restaurant',        category: 'Food',        categoryColor: '#f87171', amount: 890,   type: 'expense', date: '2025-06-11', note: 'Team lunch'       },
    { id: 11, title: 'Internet Bill',     category: 'Utilities',   categoryColor: '#60b4ff', amount: 1299,  type: 'expense', date: '2025-06-12'                           },
    { id: 12, title: 'Side Project',      category: 'Freelance',   categoryColor: '#38e8a0', amount: 8500,  type: 'income',  date: '2025-06-13'                           },
    { id: 13, title: 'Medicine',          category: 'Health',      categoryColor: '#f472b6', amount: 430,   type: 'expense', date: '2025-06-14'                           },
    { id: 14, title: 'Gym Membership',    category: 'Health',      categoryColor: '#f472b6', amount: 999,   type: 'expense', date: '2025-06-15'                           },
    { id: 15, title: 'Bonus',             category: 'Salary',      categoryColor: '#63d478', amount: 10000, type: 'income',  date: '2025-06-16', note: 'Q2 bonus'         },
]);

// ── Search & filter ───────────────────────────────────────────────
const search     = ref('');
const filterType = ref<'all' | 'income' | 'expense'>('all');
const filterCat  = ref('all');
const showFilter = ref(false);

const filteredTransactions = computed(() =>
    transactions.value.filter(t => {
        const matchSearch = t.title.toLowerCase().includes(search.value.toLowerCase())
            || t.category.toLowerCase().includes(search.value.toLowerCase());
        const matchType = filterType.value === 'all' || t.type === filterType.value;
        const matchCat  = filterCat.value  === 'all' || t.category === filterCat.value;
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

// ── Transaction actions ───────────────────────────────────────────
const showAddTxn = ref(false);

function addTransaction(txn: Omit<Transaction, 'id'>) {
    transactions.value.unshift({ id: Date.now(), ...txn });
    showAddTxn.value = false;
    currentPage.value = 1;
}

function deleteTransaction(id: number) {
    transactions.value = transactions.value.filter(t => t.id !== id);
    if (currentPage.value > totalPages.value) currentPage.value = totalPages.value;
}

// ── Category actions ──────────────────────────────────────────────
const showAddCat = ref(false);

function addCategory(cat: Omit<Category, 'id'>) {
    categories.value.push({ id: Date.now(), ...cat });
    showAddCat.value = false;
}

function deleteCategory(id: number) {
    categories.value = categories.value.filter(c => c.id !== id);
}
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
                    @add="showAddTxn = true"
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
                    @delete="deleteTransaction"
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
                :categories="categories"
                @delete="deleteCategory"
                @add="showAddCat = true"
            />

        </div>

        <!-- Modals -->
        <AddTransactionModal
            :open="showAddTxn"
            :categories="categories"
            @close="showAddTxn = false"
            @submit="addTransaction"
        />

        <AddCategoryModal
            :open="showAddCat"
            @close="showAddCat = false"
            @submit="addCategory"
        />

    </AppLayout>
</template>