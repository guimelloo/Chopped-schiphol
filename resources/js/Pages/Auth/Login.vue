<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import { useI18n } from '@/composables/useI18n.js'

const { t } = useI18n()

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head title="Inloggen — Chopped Schiphol" />

    <div class="min-h-screen bg-slate-950 flex flex-col">
        <div class="border-b border-white/5 px-4 py-3 flex items-center justify-between">
            <Link href="/" class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-400">
                    <svg class="h-4 w-4 text-slate-950" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-[10px] font-bold tracking-[0.2em] text-yellow-400 uppercase">Chopped</span>
                    <span class="text-xs font-extrabold text-white">Schiphol</span>
                </div>
            </Link>
            <LanguageSwitcher />
        </div>

        <div class="flex flex-1 items-center justify-center px-4 py-16">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-yellow-400/10 border border-yellow-400/20 mb-4">
                        <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white">Inloggen</h1>
                    <p class="mt-1 text-sm text-slate-400">Welkom terug bij Chopped Schiphol</p>
                </div>

                <div v-if="status" class="mb-5 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-300">{{ t('common.email') }}</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="naam@voorbeeld.nl"
                            :class="['w-full rounded-xl border bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 transition-all', form.errors.email ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-yellow-400/30 focus:border-yellow-400/30']"
                        />
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-300">Wachtwoord</label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs text-yellow-400 hover:text-yellow-300 transition-colors">
                                Wachtwoord vergeten?
                            </Link>
                        </div>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            :class="['w-full rounded-xl border bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 transition-all', form.errors.password ? 'border-red-500/50 focus:ring-red-500/30' : 'border-white/10 focus:ring-yellow-400/30 focus:border-yellow-400/30']"
                        />
                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <input v-model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-white/20 bg-white/5 text-yellow-400 focus:ring-yellow-400/30" />
                        <label for="remember" class="text-sm text-slate-400">Onthoud mij</label>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-xl bg-yellow-400 py-3.5 text-sm font-bold text-slate-950 hover:bg-yellow-300 active:scale-[0.98] transition-all shadow-lg shadow-yellow-400/20 disabled:opacity-50 disabled:cursor-not-allowed mt-2"
                    >
                        {{ form.processing ? 'Bezig...' : 'Inloggen' }}
                    </button>
                </form>

                <p class="mt-6 text-center text-xs text-slate-500">
                    Nog geen account?
                    <Link :href="route('register')" class="text-yellow-400 hover:text-yellow-300 font-semibold transition-colors">Registreren</Link>
                </p>
            </div>
        </div>
    </div>
</template>
