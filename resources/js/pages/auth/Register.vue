<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
</script>

<template>
    <AuthBase
        title="Create an account"
        description="Enter your details below to create your account"
    >
        <Head title="Register" />

        <!-- Background grid -->
        <div
            class="pointer-events-none fixed inset-0 z-0"
            style="background-image: linear-gradient(rgba(99,212,120,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(99,212,120,0.04) 1px, transparent 1px); background-size: 48px 48px;"
            aria-hidden="true"
        />

        <!-- Orb top-right -->
        <div
            class="fixed rounded-full pointer-events-none z-0 animate-float-slow"
            style="width:420px; height:420px; top:-100px; right:-80px; background:radial-gradient(circle, rgba(99,212,120,0.15), transparent 70%); filter:blur(80px);"
            aria-hidden="true"
        />

        <!-- Orb bottom-left -->
        <div
            class="fixed rounded-full pointer-events-none z-0 animate-float-slow"
            style="width:300px; height:300px; bottom:60px; left:-60px; background:radial-gradient(circle, rgba(56,139,253,0.12), transparent 70%); filter:blur(80px); animation-delay:3s;"
            aria-hidden="true"
        />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="relative z-10 flex flex-col gap-6 font-display"
        >
            <div class="grid gap-5">

                <!-- Name -->
                <div class="grid gap-2">
                    <Label for="name" class="font-mono text-[11px] uppercase tracking-widest text-white/50">
                        Full name
                    </Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Juan dela Cruz"
                        class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-mono text-white/90 placeholder:text-white/25 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none transition-all duration-200"
                    />
                    <InputError :message="errors.name" class="text-xs font-mono text-red-400" />
                </div>

                <!-- Email -->
                <div class="grid gap-2">
                    <Label for="email" class="font-mono text-[11px] uppercase tracking-widest text-white/50">
                        Email address
                    </Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="you@example.com"
                        class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-mono text-white/90 placeholder:text-white/25 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none transition-all duration-200"
                    />
                    <InputError :message="errors.email" class="text-xs font-mono text-red-400" />
                </div>

                <!-- Password -->
                <div class="grid gap-2">
                    <Label for="password" class="font-mono text-[11px] uppercase tracking-widest text-white/50">
                        Password
                    </Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        placeholder="••••••••"
                        class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-mono text-white/90 placeholder:text-white/25 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none transition-all duration-200"
                    />
                    <InputError :message="errors.password" class="text-xs font-mono text-red-400" />
                </div>

                <!-- Confirm Password -->
                <div class="grid gap-2">
                    <Label for="password_confirmation" class="font-mono text-[11px] uppercase tracking-widest text-white/50">
                        Confirm password
                    </Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="••••••••"
                        class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-mono text-white/90 placeholder:text-white/25 focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10 focus:outline-none transition-all duration-200"
                    />
                    <InputError :message="errors.password_confirmation" class="text-xs font-mono text-red-400" />
                </div>

                <!-- Submit -->
                <Button
                    type="submit"
                    :tabindex="5"
                    :disabled="processing"
                    data-test="register-user-button"
                    class="mt-1 w-full flex items-center justify-center gap-2 py-3.5 rounded-xl text-base font-bold text-[#0b0f1a] border-0 transition-all duration-200 hover:-translate-y-px hover:shadow-xl hover:shadow-emerald-500/25 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                    style="background: linear-gradient(135deg, #63d478, #38b858);"
                >
                    <Spinner v-if="processing" />
                    {{ processing ? 'Creating account…' : 'Create account' }}
                </Button>
            </div>

            <!-- Login link -->
            <p class="text-center font-mono text-sm text-white/40">
                Already have an account?
                <TextLink
                    :href="login()"
                    :tabindex="6"
                    class="ml-1 font-semibold text-emerald-400 hover:text-emerald-300 transition-colors duration-200"
                >
                    Log in
                </TextLink>
            </p>
        </Form>

    </AuthBase>
</template>