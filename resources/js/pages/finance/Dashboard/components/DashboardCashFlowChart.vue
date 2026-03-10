<script setup lang="ts">
interface ChartMonth {
    label: string;
    income: number;
    expense: number;
}

const props = defineProps<{
    chartMonths: ChartMonth[];
}>();

const maxVal = Math.max(...props.chartMonths.flatMap(m => [m.income, m.expense]));
const barH = (val: number, maxPx = 120) => Math.round((val / maxVal) * maxPx);
</script>

<template>
    <div
        class="rounded-2xl border border-white/8 p-6"
        style="background: rgba(255,255,255,0.03); box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);"
    >
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-base font-extrabold tracking-tight text-white">Cash Flow</h2>
                <p class="font-mono text-[11px] text-white/30 mt-0.5 uppercase tracking-widest">Last 6 months</p>
            </div>
            <div class="flex items-center gap-4 font-mono text-[11px] text-white/40">
                <span class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-emerald-400 inline-block" />
                    Income
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-red-400 inline-block" />
                    Expense
                </span>
            </div>
        </div>

        <!-- Bar chart -->
        <div class="flex items-end justify-between gap-3 h-36 px-1">
            <div
                v-for="(m, i) in chartMonths"
                :key="i"
                class="flex-1 flex flex-col items-center gap-1"
            >
                <div class="w-full flex items-end justify-center gap-1" :style="{ height: '120px' }">
                    <!-- Income bar -->
                    <div
                        class="flex-1 rounded-t-md animate-grow-up"
                        :style="{
                            height: barH(m.income) + 'px',
                            background: 'linear-gradient(to top, #3db85a, #63d478)',
                            animationDelay: (i * 0.08) + 's',
                        }"
                    />
                    <!-- Expense bar -->
                    <div
                        class="flex-1 rounded-t-md animate-grow-up"
                        :style="{
                            height: barH(m.expense) + 'px',
                            background: 'linear-gradient(to top, #b91c1c, #f87171)',
                            animationDelay: (i * 0.08 + 0.04) + 's',
                        }"
                    />
                </div>
                <span class="font-mono text-[10px] text-white/30">{{ m.label }}</span>
            </div>
        </div>
    </div>
</template>