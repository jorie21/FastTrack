<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import LoginModal from '@/components/LoginModal.vue';
import { dashboard, register } from '@/routes';

defineProps<{
    canRegister: boolean;
}>();

const page = usePage();
const showLogin = ref(false);
</script>

<template>
    <header class="relative z-10 flex items-center justify-between px-8 py-6 lg:px-12 border-b border-white/5 backdrop-blur-md">
        <!-- Logo -->
        <div class="flex items-center gap-2.5">
            <img src="/logo/FastTrackLogo.png" alt="FastTrack Logo" class="h-8 w-auto" />
            <span
                class="text-xl font-extrabold tracking-tight"
                style="background: linear-gradient(135deg, #63d478, #38e8a0); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"
            >
                FastTrack
            </span>
        </div>

        <!-- Nav links -->
        <nav class="flex items-center gap-3">
            <template v-if="page.props.auth.user">
                <Link
                    :href="dashboard()"
                    class="px-5 py-2 text-sm font-semibold rounded-lg border border-emerald-500/40 text-emerald-400 hover:bg-emerald-500/10 hover:border-emerald-400 transition-all duration-200"
                >
                    Dashboard
                </Link>
            </template>
            <template v-else>
                <!-- Opens login modal instead of navigating -->
                <button
                    type="button"
                    class="px-5 py-2 text-sm font-semibold rounded-lg border border-white/10 text-white/60 hover:bg-white/5 hover:text-white transition-all duration-200"
                    @click="showLogin = true"
                >
                    Log in
                </button>

                <Link
                    v-if="canRegister"
                    :href="register()"
                    class="px-5 py-2 text-sm font-semibold rounded-lg text-[#0b0f1a] transition-all duration-200 hover:-translate-y-px hover:shadow-lg hover:shadow-emerald-500/30"
                    style="background: linear-gradient(135deg, #63d478, #38b858);"
                >
                    Get Started
                </Link>
            </template>
        </nav>
    </header>

    <!-- Login modal -->
    <LoginModal :open="showLogin" @close="showLogin = false" />
</template>