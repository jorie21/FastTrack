<script setup lang="ts">
import { X, ChevronDown } from 'lucide-vue-next';
import { ref } from 'vue';
import type { Category, Transaction } from '../Types';

const props = defineProps<{
    open: boolean;
    categories: Category[];
}>();

const emit = defineEmits<{
    close: [];
    submit: [txn: Omit<Transaction, 'id'>];
}>();

const form = ref({
    title: '',
    amount: '',
    type: 'expense' as 'income' | 'expense',
    category: '',
    date: '',
    note: '',
});

function submit() {
    if (!form.value.title || !form.value.amount || !form.value.category) return;
    const cat = props.categories.find(c => c.name === form.value.category);
    emit('submit', {
        title: form.value.title,
        amount: Number(form.value.amount),
        type: form.value.type,
        category: form.value.category,
        categoryColor: cat?.color ?? '#63d478',
        date: form.value.date || new Date().toISOString().split('T')[0],
        note: form.value.note || undefined,
    });
    form.value = { title: '', amount: '', type: 'expense', category: '', date: '', note: '' };
}
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-center justify-center px-4"
                style="background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);"
                @click.self="emit('close')"
            >
                <div
                    class="w-full max-w-md rounded-2xl border border-white/10 p-7 font-display"
                    style="background: #0d1117; box-shadow: 0 0 0 1px rgba(99,212,120,0.08), 0 32px 80px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.06);"
                >
                    <!-- Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-extrabold tracking-tight text-white">Add Transaction</h2>
                            <p class="mt-0.5 font-mono text-xs text-white/35">Record a new income or expense</p>
                        </div>
                        <button @click="emit('close')" class="rounded-lg p-2 text-white/30 transition-all duration-200 hover:bg-white/5 hover:text-white/70">
                            <X class="h-4 w-4" />
                        </button>
                    </div>

                    <!-- Type toggle -->
                    <div class="mb-5 flex gap-2 rounded-xl border border-white/8 p-1" style="background: rgba(255,255,255,0.03);">
                        <button
                            v-for="t in ['expense', 'income']" :key="t"
                            @click="form.type = t as any"
                            class="flex-1 rounded-lg py-2 text-sm font-bold capitalize transition-all duration-200"
                            :class="form.type === t
                                ? t === 'income' ? 'border border-emerald-500/30 bg-emerald-500/20 text-emerald-400'
                                                 : 'border border-red-500/30 bg-red-500/20 text-red-400'
                                : 'text-white/35 hover:text-white/60'"
                        >{{ t }}</button>
                    </div>

                    <!-- Fields -->
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Title</label>
                            <input v-model="form.title" type="text" placeholder="e.g. Monthly Salary"
                                class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none" />
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Amount (₱)</label>
                            <input v-model="form.amount" type="number" placeholder="0.00" min="0"
                                class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none" />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Category</label>
                                <div class="relative">
                                    <select v-model="form.category"
                                        class="w-full cursor-pointer appearance-none rounded-xl border border-white/10 bg-white/5 py-3 pr-8 pl-3 font-mono text-sm text-white/80 focus:border-emerald-500/40 focus:outline-none">
                                        <option value="" disabled class="bg-[#0d1117]">Select…</option>
                                        <option v-for="c in categories" :key="c.id" :value="c.name" class="bg-[#0d1117]">{{ c.icon }} {{ c.name }}</option>
                                    </select>
                                    <ChevronDown class="pointer-events-none absolute top-1/2 right-2.5 h-3.5 w-3.5 -translate-y-1/2 text-white/30" />
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Date</label>
                                <input v-model="form.date" type="date"
                                    class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 font-mono text-sm text-white/80 [color-scheme:dark] transition-all duration-200 focus:border-emerald-500/50 focus:outline-none" />
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">
                                Note <span class="normal-case text-white/25">(optional)</span>
                            </label>
                            <input v-model="form.note" type="text" placeholder="Add a note…"
                                class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none" />
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex gap-3">
                        <button @click="emit('close')"
                            class="flex-1 rounded-xl border border-white/10 py-3 text-sm font-semibold text-white/50 transition-all duration-200 hover:bg-white/5 hover:text-white/80">
                            Cancel
                        </button>
                        <button @click="submit"
                            class="flex-1 rounded-xl py-3 text-sm font-bold text-[#0b0f1a] transition-all duration-200 hover:-translate-y-px hover:shadow-lg hover:shadow-emerald-500/25"
                            style="background: linear-gradient(135deg, #63d478, #38b858);">
                            Save Transaction
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