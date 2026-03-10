<script setup lang="ts">
import * as Icons from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    name: string;
    class?: string;
}>();

const iconComponent = computed(() => {
    // Convert kebab-case or snake_case to PascalCase if needed, 
    // but Lucide exports are PascalCase (e.g. Briefcase, Monitor)
    // If the name is 'briefcase', we look for Icons['Briefcase']
    const pascalName = props.name
        .split(/[-_ ]/)
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join('');
    
    return (Icons as any)[pascalName] || (Icons as any)[props.name];
});
</script>

<template>
    <component 
        :is="iconComponent" 
        v-if="iconComponent" 
        :class="props.class" 
    />
    <span v-else :class="props.class">{{ name }}</span>
</template>
