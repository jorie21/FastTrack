<script setup lang="ts">
defineProps<{ activeTab: 'transactions' | 'categories' }>();
const emit = defineEmits<{ 'update:activeTab': [value: 'transactions' | 'categories'] }>();
</script>

<template>
    <div
        class="flex w-fit items-center gap-1 rounded-xl border border-white/8 p-1"
        style="background: rgba(255,255,255,0.03);"
    >
        <button
            v-for="tab in [
                { key: 'transactions', label: 'Transactions' },
                { key: 'categories',   label: 'Categories'   },
            ]"
            :key="tab.key"
            @click="emit('update:activeTab', tab.key as any)"
            class="rounded-lg px-5 py-2 text-sm font-semibold transition-all duration-200"
            :class="activeTab === tab.key
                ? 'text-[#0b0f1a] shadow-lg shadow-emerald-500/20'
                : 'text-white/45 hover:text-white/70'"
            :style="activeTab === tab.key ? 'background: linear-gradient(135deg, #63d478, #38b858);' : ''"
        >
            {{ tab.label }}
        </button>
    </div>
</template>