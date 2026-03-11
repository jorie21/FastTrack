<script setup lang="ts">
import { Plus, Trash2, Pencil } from 'lucide-vue-next';
import { onMounted } from 'vue';
import DynamicIcon from '@/components/DynamicIcon.vue';
import { useCategoryState } from '@/composables/useCategoryState';

const { categories, fetchCategories, deleteCategory, editCategory, openModal } = useCategoryState();

onMounted(() => {
    fetchCategories();
});
defineEmits<{
    add: [];
}>();
</script>

<template>
    <div class="flex flex-col gap-4">

        <!-- Toolbar -->
        <div class="flex items-center justify-between">
            <p class="font-mono text-xs uppercase tracking-widest text-white/35">
                {{ categories.length }} categories
            </p>
            <button
                @click="openModal()"
                class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold text-[#0b0f1a] transition-all duration-200 hover:-translate-y-px hover:shadow-lg hover:shadow-emerald-500/25"
                style="background: linear-gradient(135deg, #63d478, #38b858);"
            >
                <Plus class="h-4 w-4" />
                Add Category
            </button>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="cat in categories"
                :key="cat.uuid"
                @click="editCategory(cat)"
                class="group relative flex cursor-pointer items-center gap-4 rounded-2xl border border-white/6 px-5 py-4 transition-all duration-200 hover:border-white/12"
                style="background: rgba(255,255,255,0.03);"
            >
                <!-- Edit Indicator (Pencil Icon) -->
                <div class="absolute top-2 right-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                    <Pencil class="h-3 w-3 text-white/20" />
                </div>

                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-xl"
                    :style="{ background: cat.color + '18' }"
                >
                    <DynamicIcon :name="cat.icon" class="w-6 h-6" :style="{ color: cat.color }" />
                </div>

                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white/90">{{ cat.name }}</p>
                    <span
                        class="rounded-full px-2 py-0.5 font-mono text-[10px] capitalize"
                        :style="{ color: cat.color, background: cat.color + '18' }"
                    >{{ cat.type }}</span>
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <div class="h-3 w-3 rounded-full" :style="{ background: cat.color }" />
                    <button
                        @click.stop="deleteCategory(cat.uuid)"
                        class="rounded-lg p-1.5 text-white/25 opacity-0 transition-all duration-200 group-hover:opacity-100 hover:bg-red-500/10 hover:text-red-400"
                    >
                        <Trash2 class="h-3.5 w-3.5" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
