<script setup lang="ts">
import { AlertTriangle } from 'lucide-vue-next';
import { useErrorState } from '@/composables/useErrorState';

const { 
    showErrorModal, 
    deleteErrorMessage, 
    closeErrorModal 
} = useErrorState();
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="showErrorModal"
                class="fixed inset-0 z-[60] flex items-center justify-center px-4"
                style="background: rgba(0,0,0,0.8); backdrop-filter: blur(8px);"
                @click.self="closeErrorModal"
            >
                <div
                    class="w-full max-w-sm rounded-2xl border border-red-500/20 p-6 font-display"
                    style="background: #0d1117; box-shadow: 0 0 0 1px rgba(239,68,68,0.1), 0 32px 80px rgba(0,0,0,0.6);"
                >
                    <!-- Header -->
                    <div class="mb-5 flex flex-col items-center text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-500/10 text-red-500">
                            <AlertTriangle class="h-7 w-7" />
                        </div>
                        <h2 class="text-lg font-extrabold tracking-tight text-white">
                            Delete Failed
                        </h2>
                        <p class="mt-2 font-mono text-xs text-white/40 leading-relaxed">
                            We encountered an error while trying to delete this item.
                        </p>
                    </div>

                    <!-- Error Content -->
                    <div class="mb-6 rounded-xl border border-white/5 bg-white/3 p-4">
                        <p class="text-center font-mono text-xs text-red-400/90">
                            {{ deleteErrorMessage || 'An unexpected error occurred.' }}
                        </p>
                    </div>

                    <!-- Action -->
                    <button 
                        @click="closeErrorModal"
                        class="w-full rounded-xl bg-white/5 py-3 text-sm font-bold text-white transition-all duration-200 hover:bg-white/10 active:scale-[0.98]"
                    >
                        Understood
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(0.9) translateY(20px); }
</style>
