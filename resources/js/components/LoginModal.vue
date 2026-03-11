<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import {  Check } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    DialogContent,
    Dialog,
    DialogHeader,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineProps<{
    open: boolean;
    status?: string;
    canResetPassword?: boolean;
    canRegister?: boolean;
}>();

const emit = defineEmits<{
    close: [];
}>();
</script>

<template>
    <Dialog :open="open" @update:open="(val) => !val && emit('close')">
        <DialogContent
            class="w-full max-w-md border border-white/10 p-8 backdrop-blur-xl rounded-2xl font-display [&>button]:text-white/30 [&>button]:hover:text-white/70"
            style="background: rgba(255,255,255,0.04); box-shadow: 0 0 0 1px rgba(99,212,120,0.08), 0 32px 80px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.08);"
        >
            <!-- Logo -->
            <div class="flex items-center justify-center gap-2.5 mb-6 text-emerald-400">
              <img src="/logo/FastTrackLogo.png" alt="FastTrack Logo" class="h-10 w-auto" />
            </div>

            <DialogHeader class="mb-6 text-center sm:text-center">
                <DialogTitle class="text-2xl font-extrabold tracking-tight text-white">
                    Welcome back
                </DialogTitle>
                <DialogDescription class="text-sm font-mono text-white/45 mt-1">
                    Log in to continue tracking your finances
                </DialogDescription>
            </DialogHeader>

            <!-- Status message -->
            <div
                v-if="status"
                class="mb-5 flex items-center gap-2 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-400"
            >
                <Check class="h-4 w-4" /> {{ status }}
            </div>

            <!-- Form — all original backend logic preserved -->
            <Form
                v-bind="store.form()"
                :reset-on-success="['password']"
                v-slot="{ errors, processing }"
                class="flex flex-col gap-6"
            >
                <div class="grid gap-5">

                    <!-- Email -->
                    <div class="grid gap-2">
                        <Label
                            for="email"
                            class="text-[11px] font-semibold uppercase tracking-widest text-white/50 font-mono"
                        >
                            Email address
                        </Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="email"
                            placeholder="you@example.com"
                            class="rounded-xl border border-white/10 bg-white/5 p-5 text-sm font-mono text-white/90 placeholder:text-white/25 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none transition-all duration-200"
                        />
                        <InputError :message="errors.email" class="text-xs font-mono text-red-400" />
                    </div>

                    <!-- Password -->
                    <div class="grid gap-2">
                        <div class="flex items-center justify-between">
                            <Label
                                for="password"
                                class="text-[11px] font-semibold uppercase tracking-widest text-white/50 font-mono"
                            >
                                Password
                            </Label>
                            <TextLink
                                v-if="canResetPassword"
                                :href="request()"
                                :tabindex="5"
                                class="text-xs font-mono font-semibold text-emerald-400 hover:text-emerald-300 transition-colors duration-200"
                            >
                                Forgot password?
                            </TextLink>
                        </div>
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            required
                            :tabindex="2"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="rounded-xl border border-white/10 bg-white/5 p-5 text-sm font-mono text-white/90 placeholder:text-white/25 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none transition-all duration-200"
                        />
                        <InputError :message="errors.password" class="text-xs font-mono text-red-400" />
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center justify-between">
                        <Label for="remember" class="flex items-center gap-3">
                            <Checkbox
                                id="remember"
                                name="remember"
                                :tabindex="3"
                                class="rounded border-white/20 bg-white/5 data-[state=checked]:bg-emerald-500 data-[state=checked]:border-emerald-500 transition-all duration-200"
                            />
                            <span class="text-sm text-white/55 cursor-pointer select-none">
                                Remember me
                            </span>
                        </Label>
                    </div>

                    <!-- Submit -->
                    <Button
                        type="submit"
                        class="mt-2 w-full flex items-center justify-center gap-2 py-3.5 rounded-xl text-base font-bold text-[#0b0f1a] border-0 transition-all duration-200 hover:-translate-y-px hover:shadow-xl hover:shadow-emerald-500/25 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        style="background: linear-gradient(135deg, #63d478, #38b858);"
                        :tabindex="4"
                        :disabled="processing"
                        data-test="login-button"
                    >
                        <Spinner v-if="processing" />
                        {{ processing ? 'Signing in…' : 'Log in' }}
                    </Button>
                </div>

                <!-- Sign up link -->
                <div
                    v-if="canRegister"
                    class="text-center text-sm font-mono text-white/40"
                >
                    Don't have an account?
                    <TextLink
                        :href="register()"
                        :tabindex="6"
                        class="ml-1 font-semibold text-emerald-400 hover:text-emerald-300 transition-colors duration-200"
                    >
                        Sign up
                    </TextLink>
                </div>
            </Form>
        </DialogContent>
    </Dialog>
</template>
