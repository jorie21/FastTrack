<script setup lang="ts">
import { X, ChevronDown, Loader2, Info } from 'lucide-vue-next';
import { onMounted, computed, ref, watch } from 'vue';
import { useTransactionState } from '@/composables/useTransactionState';
import { useWalletState } from '@/composables/useWalletState';
import InputError from '@/components/InputError.vue';
import type { Category } from '../types';

defineProps<{
    categories: Category[];
}>();

const { 
    form, 
    open, 
    closeModal, 
    isEditing, 
    isLoading,
    submit, 
    updateTransaction 
} = useTransactionState();

const { wallets, fetchWallets } = useWalletState();
const globalError = ref<string | null>(null);
const errors = ref<Record<string, string>>({});

onMounted(() => {
    fetchWallets();
});

// Clear errors when modal closes or opens
watch(open, (val) => {
    if (val) {
        globalError.value = null;
        errors.value = {};
    }
});

const selectedWallet = computed(() => {
    return wallets.value.find(w => w.id === form.value.wallet_id);
});

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
    }).format(amount);
};

async function handleSave() {
    if (isLoading.value) return;
    globalError.value = null;
    errors.value = {};

    // Basic Frontend Validation (Optional, as backend will handle it, but good for UX)
    if (form.value.type === 'expense' && selectedWallet.value) {
        if (selectedWallet.value.name !== 'Cash' && Number(form.value.amount) > Number(selectedWallet.value.balance)) {
            globalError.value = "Not enough balance in this wallet.";
            return;
        }
    }
    
    try {
        if (isEditing.value) {
            await updateTransaction();
        } else {
            await submit();
        }
    } catch (error: any) {
        if (error.response?.status === 422) {
            const backendErrors = error.response.data.errors;
            // Map backend field names (description) to frontend field names (title)
            if (backendErrors.description) {
                errors.value.title = backendErrors.description[0];
            }
            // Map other fields
            Object.keys(backendErrors).forEach(key => {
                if (key !== 'description') {
                    errors.value[key] = backendErrors[key][0];
                }
            });
            globalError.value = "Please correct the errors below.";
        } else {
            globalError.value = error.response?.data?.message || error.message || "Failed to save transaction";
        }
    }
}
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
                                {{ isEditing ? 'Edit' : 'Add' }} Transaction
                            </h2>
                            <p class="mt-0.5 font-mono text-xs text-white/35">
                                {{ isEditing ? 'Update your' : 'Record a new' }} income or expense
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

                    <!-- Type toggle -->
                    <div class="mb-5 flex gap-2 rounded-xl border border-white/8 p-1" style="background: rgba(255,255,255,0.03);">
                        <button
                            v-for="t in ['expense', 'income']" :key="t"
                            @click="form.type = t as any"
                            :disabled="isLoading"
                            class="flex-1 rounded-lg py-2 text-sm font-bold capitalize transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="form.type === t
                                ? t === 'income' ? 'border border-emerald-500/30 bg-emerald-500/20 text-emerald-400'
                                                 : 'border border-red-500/30 bg-red-500/20 text-red-400'
                                : 'text-white/35 hover:text-white/60'"
                        >{{ t }}</button>
                    </div>

                    <!-- Error Alert -->
                    <div v-if="globalError" class="mb-4 flex items-start gap-2.5 rounded-xl border border-red-500/20 bg-red-500/5 p-3.5 text-red-400">
                        <Info class="h-4 w-4 shrink-0" />
                        <p class="font-mono text-xs">{{ globalError }}</p>
                    </div>

                    <!-- Fields -->
                    <div class="flex flex-col gap-4" :class="{ 'opacity-50 pointer-events-none': isLoading }">
                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.title" type="text" placeholder="e.g. Monthly Salary"
                                :disabled="isLoading" required
                                class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none" 
                                :class="{ 'border-red-500/50': errors.title }" />
                            <InputError :message="errors.title" />
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">
                                Amount (₱) <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.amount" type="number" placeholder="0.00" min="0"
                                :disabled="isLoading" required
                                class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none"
                                :class="{ 'border-red-500/50': errors.amount }" />
                            <InputError :message="errors.amount" />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select v-model="form.category_id"
                                        :disabled="isLoading" required
                                        class="w-full cursor-pointer appearance-none rounded-xl border border-white/10 bg-white/5 py-3 pr-8 pl-3 font-mono text-sm text-white/80 focus:border-emerald-500/40 focus:outline-none"
                                        :class="{ 'border-red-500/50': errors.category_id }">
                                        <option value="" disabled class="bg-[#0d1117]">Select…</option>
                                        <option v-for="c in categories" :key="c.id" :value="c.id" class="bg-[#0d1117]">{{ c.name }}</option>
                                    </select>
                                    <ChevronDown class="pointer-events-none absolute top-1/2 right-2.5 h-3.5 w-3.5 -translate-y-1/2 text-white/30" />
                                </div>
                                <InputError :message="errors.category_id" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">
                                    Wallet <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select v-model="form.wallet_id"
                                        :disabled="isLoading" required
                                        class="w-full cursor-pointer appearance-none rounded-xl border border-white/10 bg-white/5 py-3 pr-8 pl-3 font-mono text-sm text-white/80 focus:border-emerald-500/40 focus:outline-none"
                                        :class="{ 'border-red-500/50': errors.wallet_id }">
                                        <option value="" disabled class="bg-[#0d1117]">Select…</option>
                                        <option v-for="w in wallets" :key="w.id" :value="w.id" class="bg-[#0d1117]">{{ w.name }}</option>
                                    </select>
                                    <ChevronDown class="pointer-events-none absolute top-1/2 right-2.5 h-3.5 w-3.5 -translate-y-1/2 text-white/30" />
                                </div>
                                <InputError :message="errors.wallet_id" />
                            </div>
                        </div>

                        <!-- Wallet Balance Display -->
                        <div v-if="selectedWallet" class="rounded-xl border border-white/5 bg-white/2 p-3">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-[10px] uppercase tracking-wider text-white/30">Available Balance</span>
                                <span class="font-mono text-xs font-bold" :class="Number(selectedWallet.balance) < 0 ? 'text-red-400' : 'text-emerald-400'">
                                    {{ formatCurrency(Number(selectedWallet.balance)) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.date" type="date"
                                :disabled="isLoading" required
                                class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 font-mono text-sm text-white/80 [color-scheme:dark] transition-all duration-200 focus:border-emerald-500/50 focus:outline-none"
                                :class="{ 'border-red-500/50': errors.transaction_date }" />
                            <InputError :message="errors.transaction_date" />
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">
                                Note <span class="normal-case text-white/25">(optional)</span>
                            </label>
                            <input v-model="form.note" type="text" placeholder="Add a note…"
                                :disabled="isLoading"
                                class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none"
                                :class="{ 'border-red-500/50': errors.note }" />
                            <InputError :message="errors.note" />
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
                            {{ isLoading ? (isEditing ? 'Updating...' : 'Saving...') : (isEditing ? 'Update' : 'Save') + ' Transaction' }}
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
