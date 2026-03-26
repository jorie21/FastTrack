<script setup lang="ts">
import { X, ChevronDown, Loader2, Wallet as WalletIcon } from 'lucide-vue-next';
import { useWalletState } from '@/composables/useWalletState';
import AlertError from '@/components/AlertError.vue';

const { 
    form, 
    open, 
    closeModal, 
    isEditing, 
    isLoading,
    errorMessage,
    submit, 
    updateWallet 
} = useWalletState();

async function handleSave() {
    if (isLoading.value) return;
    
    if (isEditing.value) {
        await updateWallet();
    } else {
        await submit();
    }
}

const walletTypes = [
    { label: 'Digital Wallet', value: 'digital' },
    { label: 'Bank Account', value: 'bank' },
    { label: 'Cash', value: 'cash' },
    { label: 'Credit Card', value: 'credit' },
    { label: 'Others', value: 'others' },
];
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-center justify-center px-4"
                style="background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);"
                @click.self="!isLoading && closeModal()"
            >
                <div
                    class="w-full max-w-md rounded-2xl border border-white/10 p-7 font-display"
                    style="background: #0d1117; box-shadow: 0 0 0 1px rgba(99,212,120,0.08), 0 32px 80px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.06);"
                >
                    <!-- Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-extrabold tracking-tight text-white">
                                {{ isEditing ? 'Edit' : 'Add' }} Wallet
                            </h2>
                            <p class="mt-0.5 font-mono text-xs text-white/35">
                                {{ isEditing ? 'Update your' : 'Create a new' }} wallet for your transactions
                            </p>
                        </div>
                        <button 
                            @click="closeModal" 
                            :disabled="isLoading"
                            class="rounded-lg p-2 text-white/30 transition-all duration-200 hover:bg-white/5 hover:text-white/70 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>

                    <!-- Error Message -->
                    <AlertError v-if="errorMessage" :title="errorMessage" class="mb-4" />

                    <!-- Fields -->
                    <div class="flex flex-col gap-4" :class="{ 'opacity-50 pointer-events-none': isLoading }">
                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Wallet Name</label>
                            <input v-model="form.name" type="text" placeholder="e.g. GCash, BPI, My Cash"
                                :disabled="isLoading"
                                class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none" />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Type</label>
                                <div class="relative">
                                    <select v-model="form.type"
                                        :disabled="isLoading"
                                        class="w-full cursor-pointer appearance-none rounded-xl border border-white/10 bg-white/5 py-3 pr-8 pl-3 font-mono text-sm text-white/80 focus:border-emerald-500/40 focus:outline-none">
                                        <option v-for="t in walletTypes" :key="t.value" :value="t.value" class="bg-[#0d1117]">{{ t.label }}</option>
                                    </select>
                                    <ChevronDown class="pointer-events-none absolute top-1/2 right-2.5 h-3.5 w-3.5 -translate-y-1/2 text-white/30" />
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Initial Balance (₱)</label>
                                <input v-model="form.balance" type="number" placeholder="0.00" min="0"
                                    :disabled="isLoading"
                                    class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none" />
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Icon & Color</label>
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-emerald-400">
                                    <WalletIcon class="h-5 w-5" />
                                </div>
                                <input v-model="form.color" type="color"
                                    :disabled="isLoading"
                                    class="h-11 w-20 cursor-pointer rounded-xl border border-white/10 bg-white/5 p-1 transition-all duration-200 focus:border-emerald-500/50 focus:outline-none" />
                                <div class="flex-1">
                                     <input v-model="form.icon" type="text" placeholder="Icon name (e.g. Wallet)"
                                        :disabled="isLoading"
                                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex gap-3">
                        <button @click="closeModal"
                            :disabled="isLoading"
                            class="flex-1 rounded-xl border border-white/10 py-3 text-sm font-semibold text-white/50 transition-all duration-200 hover:bg-white/5 hover:text-white/80 disabled:opacity-50 disabled:cursor-not-allowed">
                            Cancel
                        </button>
                        <button @click="handleSave"
                            :disabled="isLoading"
                            class="flex-1 flex items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold text-[#0b0f1a] transition-all duration-200 hover:-translate-y-px hover:shadow-lg hover:shadow-emerald-500/25 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                            style="background: linear-gradient(135deg, #63d478, #38b858);">
                            <Loader2 v-if="isLoading" class="h-4 w-4 animate-spin" />
                            {{ isLoading ? (isEditing ? 'Updating...' : 'Saving...') : (isEditing ? 'Update' : 'Save') + ' Wallet' }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(0.96) translateY(8px); }
</style>
