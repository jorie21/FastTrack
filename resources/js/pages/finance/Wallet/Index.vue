<script setup lang="ts">
import {  Plus, Search } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import AppContent from '@/components/AppContent.vue';
import AlertError from '@/components/AlertError.vue';
import { useWalletState } from '@/composables/useWalletState';
import AppLayout from '@/layouts/AppLayout.vue';
import AddWalletModal from './components/AddWalletModal.vue';
import WalletList from './components/WalletList.vue';

const { wallets, fetchWallets, openModal, isLoading, errorMessage } = useWalletState();
const searchQuery = ref('');

onMounted(() => {
    fetchWallets();
});

const handleSearch = () => {
    fetchWallets(); // The filterByRequest in backend handles 'keyword'
};
</script>

<template>
    <AppLayout>
        <AppContent>
            <div class="space-y-6 p-6 font-display">
                <!-- Header Section -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl font-extrabold tracking-tight text-white sm:text-2xl flex items-center gap-2">My Wallets</h1>
                        <p class="mt-1 font-mono text-[10px] sm:text-xs text-white/35 uppercase tracking-widest">
                            Manage your digital wallets, bank accounts, and cash
                        </p>
                    </div>
                    
                    <button 
                        @click="openModal"
                        class="group flex items-center justify-center gap-2 rounded-xl py-2.5 px-5 text-sm font-bold text-[#0b0f1a] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/20 active:translate-y-0 active:shadow-none"
                        style="background: linear-gradient(135deg, #63d478, #38b858);"
                    >
                        <Plus class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" />
                        <span>Add Wallet</span>
                    </button>
                </div>

                <!-- Stats & Search (Optional placeholder for now) -->
                <div class="flex flex-col gap-4 rounded-2xl border border-white/8 bg-white/3 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative flex-1 max-w-sm">
                        <Search class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-white/25" />
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search wallets..." 
                            class="w-full rounded-xl border border-white/10 bg-white/5 py-2.5 pl-10 pr-4 font-mono text-xs text-white/70 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/40 focus:ring-2 focus:ring-emerald-500/5 focus:outline-none"
                            @input="handleSearch"
                        />
                    </div>
                </div>

                <!-- Error Message -->
                <AlertError v-if="errorMessage" :title="errorMessage" class="mb-4" />

                <!-- Wallets Grid -->
                <div v-if="isLoading && wallets.length === 0" class="flex h-64 items-center justify-center">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-emerald-500/20 border-t-emerald-500"></div>
                </div>
                
                <WalletList v-else :wallets="wallets" />

                <!-- Modals -->
                <AddWalletModal />
            </div>
        </AppContent>
    </AppLayout>
</template>
