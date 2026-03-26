<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import DeleteErrorModal from '@/pages/finance/Wallet/components/DeleteErrorModal.vue';
import { useConfirm } from '@/composables/useConfirm';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

const { state, confirm, cancel } = useConfirm();

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
        <ConfirmationModal 
            :show="state.show"
            :title="state.title"
            :message="state.message"
            :isLoading="state.isLoading"
            @confirm="confirm"
            @cancel="cancel"
        />
        <DeleteErrorModal />
    </AppShell>
</template>
