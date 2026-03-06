<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import WelcomeBackground from '@/components/Welcome/WelcomeBackground.vue';
import WelcomeDashboardPreview from '@/components/Welcome/WelcomeDashboardPreview.vue';
import WelcomeHeroContent from '@/components/Welcome/WelcomeHeroContent.vue';
import WelcomeNav from '@/components/Welcome/WelcomeNav.vue';


withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Fast Track — Expense & Income Tracker">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com"/>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet" />
    </Head>

    <div
        class="relative min-h-screen flex flex-col overflow-hidden font-display text-[#e8eaf0]"
        style="background: #0b0f1a;"
    >
        <WelcomeBackground />

        <WelcomeNav :can-register="canRegister" />

        <!-- Hero: single centered column on mobile, two columns on lg+ -->
        <main class="relative  z-10 flex-1 flex items-center justify-center w-full px-6 py-12 lg:px-12 lg:py-0">
            <div class="w-full p-10 max-w-6xl flex flex-col items-center gap-12 lg:flex-row lg:items-center lg:gap-16">
                <WelcomeHeroContent :can-register="canRegister" />
                <WelcomeDashboardPreview />
            </div>
        </main>
    </div>
</template>