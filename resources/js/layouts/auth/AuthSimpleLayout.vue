<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">

                    <!-- Logo -->
                    <Link :href="home()" class="flex flex-col items-center gap-2">
                        <div class="flex items-center gap-2">
                             <img src="/logo/FastTrackLogo.png" alt="FastTrack Logo" class="h-8 w-auto" />
                            <span
                                class="text-xl font-extrabold tracking-tight font-display"
                                style="background: linear-gradient(135deg, #63d478, #38e8a0); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"
                            >
                                FastTrack
                            </span>
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>

                    <!-- Title + description -->
                    <div class="space-y-2 text-center">
                        <h1 class="font-display text-xl font-extrabold tracking-tight text-white">
                            {{ title }}
                        </h1>
                        <p class="font-mono text-xs uppercase tracking-widest text-white/40">
                            {{ description }}
                        </p>
                    </div>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>