<script setup lang="ts">
import { AlertTriangle, Loader2 } from 'lucide-vue-next';

interface Props {
    show: boolean;
    title?: string;
    message?: string;
    confirmText?: string;
    cancelText?: string;
    isLoading?: boolean;
    variant?: 'danger' | 'warning' | 'info';
}

withDefaults(defineProps<Props>(), {
    title: 'Are you sure?',
    message: 'This action cannot be undone.',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    isLoading: false,
    variant: 'danger'
});

const emit = defineEmits<{
    confirm: [];
    cancel: [];
}>();
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="show"
                class="fixed inset-0 z-[100] flex items-center justify-center px-4"
                style="background: rgba(0,0,0,0.8); backdrop-filter: blur(8px);"
                @click.self="!isLoading && emit('cancel')"
            >
                <div
                    class="w-full max-w-sm rounded-2xl border p-6 font-display"
                    :class="[
                        variant === 'danger' ? 'border-red-500/20' : 'border-amber-500/20',
                    ]"
                    style="background: #0d1117; box-shadow: 0 32px 80px rgba(0,0,0,0.6);"
                >
                    <!-- Header -->
                    <div class="mb-5 flex flex-col items-center text-center">
                        <div 
                            class="mb-4 flex h-14 w-14 items-center justify-center rounded-full"
                            :class="[
                                variant === 'danger' ? 'bg-red-500/10 text-red-500' : 'bg-amber-500/10 text-amber-500',
                            ]"
                        >
                            <AlertTriangle class="h-7 w-7" />
                        </div>
                        <h2 class="text-lg font-extrabold tracking-tight text-white">
                            {{ title }}
                        </h2>
                        <p class="mt-2 font-mono text-xs text-white/40 leading-relaxed">
                            {{ message }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 mt-6">
                        <button 
                            @click="emit('cancel')"
                            :disabled="isLoading"
                            class="flex-1 rounded-xl bg-white/5 py-3 text-sm font-bold text-white transition-all duration-200 hover:bg-white/10 disabled:opacity-50"
                        >
                            {{ cancelText }}
                        </button>
                        <button 
                            @click="emit('confirm')"
                            :disabled="isLoading"
                            class="flex-1 flex items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold text-[#0b0f1a] transition-all duration-200 hover:-translate-y-px hover:shadow-lg disabled:opacity-50"
                            :class="[
                                variant === 'danger' ? 'bg-red-500 hover:shadow-red-500/25' : 'bg-amber-500 hover:shadow-amber-500/25',
                            ]"
                            :style="variant === 'danger' ? '' : 'background: linear-gradient(135deg, #f59e0b, #d97706);'"
                        >
                            <Loader2 v-if="isLoading" class="h-4 w-4 animate-spin" />
                            {{ confirmText }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(0.9) translateY(20px); }
</style>
