<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

import DashboardCashFlowChart      from './components/DashboardCashFlowChart.vue';
import DashboardGreetings          from './components/DashboardGreetings.vue';
import DashboardRecentTransactions from './components/DashboardRecentTransactions.vue';
import DashboardStatsCards         from './components/DashboardStatsCards.vue';
import DashboardTopSpending        from './components/DashboardTopSpending.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
];

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4 sm:p-6 font-display">

            <DashboardGreetings />

            <DashboardStatsCards />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="lg:col-span-2">
                    <DashboardCashFlowChart />
                </div>
                <DashboardTopSpending />
            </div>

            <DashboardRecentTransactions />

        </div>
    </AppLayout>
</template>