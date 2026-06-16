<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import { useI18n } from '@/composables/useI18n.js'

const { t } = useI18n()

const formulier = useForm({
    gebruikersnaam: '',
    wachtwoord: '',
})

function inloggen() {
    formulier.post('/directeur/inloggen', {
        onFinish: () => formulier.reset('wachtwoord'),
    })
}
</script>

<template>
    <Head :title="t('director.login')" />

    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-gray-900 to-gray-700 px-4">
        <div class="w-full max-w-md">
            <div class="mb-2 flex justify-end">
                <LanguageSwitcher />
            </div>

            <div class="mb-8 text-center text-white">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-yellow-400 shadow-lg">
                    <svg class="h-9 w-9 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Schiphol Vluchtbeheer</h1>
                <p class="mt-1 text-gray-300">{{ t('director.portal') }}</p>
            </div>

            <div class="rounded-2xl bg-white p-8 shadow-2xl">
                <h2 class="mb-6 text-xl font-bold text-gray-900">{{ t('director.login') }}</h2>

                <div v-if="formulier.errors.gebruikersnaam" class="mb-5 flex items-center gap-3 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ formulier.errors.gebruikersnaam }}
                </div>

                <form @submit.prevent="inloggen" class="space-y-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">{{ t('director.username') }}</label>
                        <input
                            v-model="formulier.gebruikersnaam"
                            type="text"
                            required
                            autocomplete="username"
                            placeholder="directeur"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200"
                            :class="{ 'border-red-400 bg-red-50': formulier.errors.gebruikersnaam }"
                        />
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">{{ t('director.password') }}</label>
                        <input
                            v-model="formulier.wachtwoord"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="formulier.processing"
                        class="w-full rounded-lg bg-gray-900 py-3 font-bold text-white transition hover:bg-gray-800 disabled:opacity-60"
                    >
                        {{ formulier.processing ? t('director.loggingIn') : t('director.loginBtn') }}
                    </button>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-5 text-center text-xs text-gray-400">
                    {{ t('director.adminAccount') }}: <strong>directeur</strong> / directeur123
                </div>
            </div>
        </div>
    </div>
</template>
