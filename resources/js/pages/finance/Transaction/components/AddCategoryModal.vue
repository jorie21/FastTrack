<script setup lang="ts">
import { X, ChevronDown, Loader2 } from 'lucide-vue-next';
import { ref } from 'vue';
import DynamicIcon from '@/components/DynamicIcon.vue';
import { useCategoryState } from '@/composables/useCategoryState';

const { form, submit, updateCategory, open, closeModal, isEditing } = useCategoryState();

const loading = ref(false);

const colorOptions = [
    '#63d478','#38e8a0','#f87171','#fb923c',
    '#60b4ff','#c084fc','#f472b6','#facc15','#34d399','#e879f9',
];

async function handleSave() {
    if (loading.value) return;

    try {
        loading.value = true;
        if (isEditing.value) {
            await updateCategory();
        } else {
            await submit();
        }
    } catch (error) {
        console.error('Action failed:', error);
    } finally {
        loading.value = false;
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
                @click.self="!loading && closeModal()"
            >
                <div
                    class="w-full max-w-sm rounded-2xl border border-white/10 p-7 font-display"
                    style="background: #0d1117; box-shadow: 0 0 0 1px rgba(99,212,120,0.08), 0 32px 80px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.06);"
                >
                    <!-- Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-extrabold tracking-tight text-white">
                                {{ isEditing ? 'Edit' : 'Add' }} Category
                            </h2>
                            <p class="mt-0.5 font-mono text-xs text-white/35">
                                {{ isEditing ? 'Update existing' : 'Create a custom' }} category
                            </p>
                        </div>
                        <button 
                            @click="closeModal()" 
                            :disabled="loading"
                            class="rounded-lg p-2 text-white/30 transition-all duration-200 hover:bg-white/5 hover:text-white/70 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>

                    <div class="flex flex-col gap-4" :class="{ 'opacity-50 pointer-events-none': loading }">
                        <!-- Name -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Name</label>
                            <input v-model="form.name" type="text" placeholder="e.g. Rent"
                                :disabled="loading"
                                class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-white/90 transition-all duration-200 placeholder:text-white/20 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none" />
                        </div>

                        <!-- Icon + Type -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Icon Name</label>
                                <div class="relative">
                                    <input v-model="form.icon" type="text" placeholder="e.g. Heart"
                                        :disabled="loading"
                                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm transition-all duration-200 focus:border-emerald-500/50 focus:outline-none" />
                                    <DynamicIcon :name="form.icon" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/20" />
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Type</label>
                                <div class="relative">
                                    <select v-model="form.type"
                                        :disabled="loading"
                                        class="w-full cursor-pointer appearance-none rounded-xl border border-white/10 bg-white/5 py-3 pr-8 pl-3 font-mono text-sm text-white/80 focus:border-emerald-500/40 focus:outline-none">
                                        <option value="expense" class="bg-[#0d1117]">Expense</option>
                                        <option value="income"  class="bg-[#0d1117]">Income</option>
                                        <option value="both"    class="bg-[#0d1117]">Both</option>
                                    </select>
                                    <ChevronDown class="pointer-events-none absolute top-1/2 right-2.5 h-3.5 w-3.5 -translate-y-1/2 text-white/30" />
                                </div>
                            </div>
                        </div>

                        <!-- Color picker -->
                        <div class="flex flex-col gap-2">
                            <label class="font-mono text-[11px] uppercase tracking-widest text-white/45">Color</label>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="color in colorOptions" :key="color"
                                    @click="form.color = color"
                                    :disabled="loading"
                                    class="h-8 w-8 rounded-full border-2 transition-all duration-200 hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                    :style="{ background: color, borderColor: form.color === color ? 'white' : 'transparent' }"
                                />
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="flex items-center gap-3 rounded-xl border border-white/8 px-4 py-3" style="background: rgba(255,255,255,0.03);">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl text-lg" :style="{ background: form.color + '18' }">
                                <DynamicIcon :name="form.icon" class="w-5 h-5" :style="{ color: form.color }" />
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white/80">{{ form.name || 'Category name' }}</p>
                                <span class="rounded-full px-2 py-0.5 font-mono text-[10px] capitalize"
                                    :style="{ color: form.color, background: form.color + '18' }">{{ form.type }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex gap-3">
                        <button @click="closeModal()"
                            :disabled="loading"
                            class="flex-1 rounded-xl border border-white/10 py-3 text-sm font-semibold text-white/50 transition-all duration-200 hover:bg-white/5 hover:text-white/80 disabled:opacity-50 disabled:cursor-not-allowed">
                            Cancel
                        </button>
                        <button @click="handleSave"
                            :disabled="loading"
                            class="flex-1 flex items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold text-[#0b0f1a] transition-all duration-200 hover:-translate-y-px hover:shadow-lg hover:shadow-emerald-500/25 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                            style="background: linear-gradient(135deg, #63d478, #38b858);">
                            <Loader2 v-if="loading" class="h-4 w-4 animate-spin" />
                            {{ loading ? (isEditing ? 'Updating...' : 'Saving...') : (isEditing ? 'Update' : 'Save') + ' Category' }}
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
