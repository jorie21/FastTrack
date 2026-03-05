<script setup lang="ts">
interface BarItem {
    label: string;
    inc: number;
    exp: number;
}

interface Transaction {
    icon: string;
    name: string;
    cat: string;
    amount: string;
    type: 'income' | 'expense';
}

const bars: BarItem[] = [
    { label: 'Jan', inc: 60, exp: 35 },
    { label: 'Feb', inc: 80, exp: 45 },
    { label: 'Mar', inc: 55, exp: 40 },
    { label: 'Apr', inc: 90, exp: 50 },
    { label: 'May', inc: 70, exp: 30 },
    { label: 'Jun', inc: 100, exp: 55 },
];

const transactions: Transaction[] = [
    { icon: '💼', name: 'Freelance Project', cat: 'Income',      amount: '+₱12,000', type: 'income'  },
    { icon: '🛒', name: 'Grocery Store',     cat: 'Food & Home', amount: '−₱1,450',  type: 'expense' },
    { icon: '💡', name: 'Electricity Bill',  cat: 'Utilities',   amount: '−₱980',    type: 'expense' },
    { icon: '📦', name: 'Online Sales',      cat: 'Income',      amount: '+₱3,200',  type: 'income'  },
];
</script>

<template>
    <div class="relative flex-1 flex justify-center max-w-[480px] w-full" aria-hidden="true">

        <!-- Main card -->
        <div class="w-full rounded-2xl p-7 backdrop-blur-xl border border-white/10 animate-slide-in-right"
            style="background: rgba(255,255,255,0.04); box-shadow: 0 0 0 1px rgba(99,212,120,0.08), 0 32px 80px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.08);"
        >
            <!-- Card header -->
            <div class="flex items-center justify-between mb-6">
                <span class="text-base font-bold text-white">Cash Flow</span>
                <span class="font-mono text-xs text-white/40 bg-white/6 px-3 py-1 rounded-full">
                    This Month
                </span>
            </div>

            <!-- Bar chart -->
            <div class="mb-6">
                <div class="flex items-end gap-2.5 h-28 mb-5">
                    <div
                        v-for="(bar, i) in bars"
                        :key="i"
                        class="flex-1 flex items-end gap-0.5 relative"
                    >
                        <!-- Income bar -->
                        <div
                            class="w-3.5 rounded-t-sm animate-grow-up"
                            :style="{
                                height: bar.inc + 'px',
                                background: 'linear-gradient(to top, #3db85a, #63d478)',
                                animationDelay: (i * 0.08) + 's',
                            }"
                        />
                        <!-- Expense bar -->
                        <div
                            class="w-3.5 rounded-t-sm animate-grow-up"
                            :style="{
                                height: bar.exp + 'px',
                                background: 'linear-gradient(to top, #b91c1c, #f87171)',
                                animationDelay: (i * 0.08 + 0.04) + 's',
                            }"
                        />
                        <!-- Month label -->
                        <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 font-mono text-[10px] text-white/30 whitespace-nowrap">
                            {{ bar.label }}
                        </span>
                    </div>
                </div>

                <!-- Legend -->
                <div class="flex items-center gap-2 font-mono text-[11px] text-white/45 mt-6">
                    <span class="inline-block w-2 h-2 rounded-sm bg-emerald-400" />
                    Income
                    <span class="inline-block w-2 h-2 rounded-sm bg-red-400 ml-3" />
                    Expense
                </div>
            </div>

            <!-- Transaction list -->
            <div class="flex flex-col gap-2.5">
                <div
                    v-for="(txn, i) in transactions"
                    :key="i"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-white/6 animate-fade-in-up"
                    style="background: rgba(255,255,255,0.03);"
                    :style="{ animationDelay: (0.5 + i * 0.1) + 's' }"
                >
                    <!-- Icon -->
                    <div
                        class="w-9 h-9 rounded-xl flex items-center justify-center text-base shrink-0"
                        :class="txn.type === 'income' ? 'bg-emerald-500/12' : 'bg-red-500/12'"
                    >
                        {{ txn.icon }}
                    </div>

                    <!-- Info -->
                    <div class="flex-1 flex flex-col gap-0.5 min-w-0">
                        <span class="text-[13px] font-semibold text-white/90 truncate">{{ txn.name }}</span>
                        <span class="font-mono text-[11px] text-white/40">{{ txn.cat }}</span>
                    </div>

                    <!-- Amount -->
                    <span
                        class="font-mono text-sm font-medium shrink-0"
                        :class="txn.type === 'income' ? 'text-emerald-400' : 'text-red-400'"
                    >
                        {{ txn.amount }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Floating badge: top right -->
        <div class="absolute -top-4 -right-6 flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold text-emerald-400 border border-white/12 backdrop-blur-xl whitespace-nowrap animate-float-badge"
            style="background: rgba(15,20,35,0.9); box-shadow: 0 8px 32px rgba(0,0,0,0.4);"
        >
            <span>📈</span> +18% vs last month
        </div>

        <!-- Floating badge: bottom left -->
        <div class="absolute bottom-6 -left-8 flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold text-blue-400 border border-white/12 backdrop-blur-xl whitespace-nowrap animate-float-badge"
            style="background: rgba(15,20,35,0.9); box-shadow: 0 8px 32px rgba(0,0,0,0.4); animation-delay: 2s;"
        >
            <span>✅</span> Budget on track
        </div>

    </div>
</template>