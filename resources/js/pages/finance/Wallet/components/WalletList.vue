<script setup lang="ts">
import { Wallet as WalletIcon, MoreVertical, Edit2, Trash2, Landmark, CreditCard, Banknote, LayoutGrid } from 'lucide-vue-next';
import { useWalletState } from '@/composables/useWalletState';
import type { Wallet } from '../types';

defineProps<{
    wallets: Wallet[];
}>();

const { editWallet, deleteWallet } = useWalletState();

function getIcon(type: string) {
    switch (type) {
        case 'bank': return Landmark;
        case 'credit': return CreditCard;
        case 'cash': return Banknote;
        case 'digital': return WalletIcon;
        default: return LayoutGrid;
    }
}

function formatCurrency(amount: number) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
    }).format(amount);
}
</script>

<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div 
            v-for="wallet in wallets" 
            :key="wallet.id"
            class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-5 transition-all duration-300 hover:border-emerald-500/30 hover:bg-white/8"
        >
            <div class="flex items-start justify-between">
                <div 
                    class="flex h-12 w-12 items-center justify-center rounded-xl transition-transform duration-300 group-hover:scale-110"
                    :style="{ backgroundColor: wallet.color + '20', color: wallet.color }"
                >
                    <component :is="getIcon(wallet.type)" class="h-6 w-6" />
                </div>
                
                <div class="flex gap-1 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                    <button 
                        @click="editWallet(wallet)"
                        class="rounded-lg p-2 text-white/40 transition-colors hover:bg-white/5 hover:text-emerald-400"
                    >
                        <Edit2 class="h-4 w-4" />
                    </button>
                    <button 
                        @click="deleteWallet(wallet.uuid)"
                        class="rounded-lg p-2 text-white/40 transition-colors hover:bg-white/5 hover:text-red-400"
                    >
                        <Trash2 class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="font-display text-sm font-bold text-white/90">{{ wallet.name }}</h3>
                <p class="font-mono text-[10px] uppercase tracking-wider text-white/30">{{ wallet.type }}</p>
                <div class="mt-3 flex items-baseline gap-1">
                    <span class="font-mono text-xl font-bold tracking-tight text-white">
                        {{ formatCurrency(wallet.balance) }}
                    </span>
                </div>
            </div>

            <!-- Background Decoration -->
            <div 
                class="absolute -right-4 -bottom-4 h-24 w-24 opacity-[0.03] transition-transform duration-500 group-hover:scale-125"
                :style="{ color: wallet.color }"
            >
                <component :is="getIcon(wallet.type)" class="h-full w-full" />
            </div>
        </div>

        <!-- Add New Wallet Card -->
        <button 
            @click="useWalletState().openModal()"
            class="flex flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-white/5 bg-transparent p-5 transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/5 group"
        >
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/5 text-white/20 transition-all duration-300 group-hover:bg-emerald-500/20 group-hover:text-emerald-400">
                <WalletIcon class="h-6 w-6" />
            </div>
            <span class="font-display text-sm font-bold text-white/30 transition-colors group-hover:text-emerald-400">Add New Wallet</span>
        </button>
    </div>
</template>
