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
        <!-- Animated background (grid + orbs) -->
        <WelcomeBackground />

        <!-- Navigation -->
        <WelcomeNav :can-register="canRegister" />

        <!-- Hero section -->
        <main class="relative z-10 flex items-center gap-16 px-8 py-16 lg:px-12 lg:py-20  mx-auto w-full min-h-[calc(100vh-80px)] flex-col lg:flex-row">
            <WelcomeHeroContent :can-register="canRegister" />
            <WelcomeDashboardPreview />
        </main>
    </div>
</template>