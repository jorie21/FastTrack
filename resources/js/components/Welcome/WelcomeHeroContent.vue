<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
import { dashboard, login, register } from '@/routes';


defineProps<{
    canRegister: boolean;
}>();

const page = usePage();

const stats = [
    { value: '+₱24,500', label: 'Income this month',   color: 'text-emerald-400' },
    { value: '−₱9,230',  label: 'Expenses this month', color: 'text-red-400'     },
    { value: '₱15,270',  label: 'Net balance',          color: 'text-blue-400'    },
];
</script>

<template>
    <!-- Takes up half the row on desktop, full width on mobile, centered on mobile -->
    <div class="w-full lg:flex-1 lg:max-w-xl flex flex-col items-center text-center lg:items-start lg:text-left animate-fade-in-up">

        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 mb-7 rounded-full border border-emerald-500/25 bg-emerald-500/10 text-emerald-400 text-xs font-semibold tracking-wide font-mono">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
            Financial clarity in seconds
        </div>

        <!-- Headline -->
        <h1 class="mb-6 font-extrabold leading-[1.05] tracking-[-2.5px]"
            style="font-size: clamp(40px, 5.5vw, 72px);"
        >
            Track
            <span style="background: linear-gradient(135deg, #63d478, #38e8a0); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                Every
            </span>
            <br />Peso.<br />
            <span class="text-white/30">In &amp; Out.</span>
        </h1>

        <!-- Subheadline -->
        <p class="mb-10 text-[17px] leading-relaxed text-white/55 max-w-md">
            FastTrack gives you a crystal-clear picture of your cash flow —
            where it comes from, where it goes, and how fast it moves.
        </p>

        <!-- CTA buttons -->
        <div class="flex flex-wrap justify-center lg:justify-start items-center gap-3.5 mb-12">
            <template v-if="page.props.auth.user">
                <Link
                    :href="dashboard()"
                    class="px-7 py-3.5 text-base font-bold rounded-xl text-[#0b0f1a] flex items-center gap-2 transition-all duration-200 hover:-translate-y-px hover:shadow-xl hover:shadow-emerald-500/30"
                    style="background: linear-gradient(135deg, #63d478, #38b858);"
                >
                    Go to Dashboard <ArrowRight class="w-5 h-5" />
                </Link>
            </template>
            <template v-else>
                <Link
                    v-if="canRegister"
                    :href="register()"
                    class="px-7 py-3.5 text-base font-bold rounded-xl text-[#0b0f1a] flex items-center gap-2 transition-all duration-200 hover:-translate-y-px hover:shadow-xl hover:shadow-emerald-500/30"
                    style="background: linear-gradient(135deg, #63d478, #38b858);"
                >
                    Start Tracking Free <ArrowRight class="w-5 h-5" />
                </Link>
                <Link
                    :href="login()"
                    class="px-7 py-3.5 text-base font-bold rounded-xl border border-white/10 text-white/60 hover:bg-white/5 hover:text-white transition-all duration-200"
                >
                    Log in
                </Link>
            </template>
        </div>

        <!-- Stats row -->
        <div class="flex flex-wrap justify-center lg:justify-start items-center gap-6 pt-5 border-t border-white/8 w-full">
            <template v-for="(stat, i) in stats" :key="i">
                <div class="flex flex-col gap-1">
                    <span :class="['font-mono text-xl font-medium tracking-tight', stat.color]">
                        {{ stat.value }}
                    </span>
                    <span class="font-mono text-[11px] uppercase tracking-wider text-white/35">
                        {{ stat.label }}
                    </span>
                </div>
                <div v-if="i < stats.length - 1" class="hidden sm:block w-px h-9 bg-white/10" />
            </template>
        </div>

    </div>
</template>
