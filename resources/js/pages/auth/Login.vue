<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LoginModal from '@/components/LoginModal.vue';
import AuthBase from '@/layouts/AuthLayout.vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase
        title="Log in to your account"
        description="Enter your email and password below to log in"
    >
        <Head title="Log in" />

        <LoginModal
            :open="true"
            :status="status"
            :can-reset-password="canResetPassword"
            :can-register="canRegister"
        />
    </AuthBase>
</template>