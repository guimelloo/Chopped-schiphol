<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import DirecteurLayout from '@/Layouts/DirecteurLayout.vue'
import { useI18n } from '@/composables/useI18n.js'

const { t } = useI18n()

const formulier = useForm({
    naam: '',
    gebruikersnaam: '',
    email: '',
    wachtwoord: '',
    wachtwoord_bevestiging: '',
    actief: true,
})

function opslaan() {
    formulier.post('/directeur/coordinatoren')
}
</script>

<template>
    <Head :title="t('director.addCoordinator')" />
    <DirecteurLayout>
        <div class="mb-6 flex items-center gap-4">
            <Link href="/directeur/coordinatoren" class="flex items-center gap-2 text-sm text-blue-700 hover:text-blue-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ t('common.back') }}
            </Link>
            <h1 class="text-2xl font-bold text-gray-900">{{ t('director.addCoordinator') }}</h1>
        </div>

        <div class="max-w-2xl">
            <form @submit.prevent="opslaan" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('common.name') }} <span class="text-red-500">*</span></label>
                        <input
                            v-model="formulier.naam"
                            type="text"
                            required
                            placeholder="Jan de Vries"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            :class="{ 'border-red-500': formulier.errors.naam }"
                        />
                        <p v-if="formulier.errors.naam" class="mt-1 text-xs text-red-600">{{ formulier.errors.naam }}</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Gebruikersnaam <span class="text-red-500">*</span></label>
                        <input
                            v-model="formulier.gebruikersnaam"
                            type="text"
                            required
                            placeholder="jan.devries"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            :class="{ 'border-red-500': formulier.errors.gebruikersnaam }"
                        />
                        <p v-if="formulier.errors.gebruikersnaam" class="mt-1 text-xs text-red-600">{{ formulier.errors.gebruikersnaam }}</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('common.email') }}</label>
                        <input
                            v-model="formulier.email"
                            type="email"
                            placeholder="jan@schiphol.nl"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Wachtwoord <span class="text-red-500">*</span></label>
                        <input
                            v-model="formulier.wachtwoord"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            :class="{ 'border-red-500': formulier.errors.wachtwoord }"
                        />
                        <p v-if="formulier.errors.wachtwoord" class="mt-1 text-xs text-red-600">{{ formulier.errors.wachtwoord }}</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Wachtwoord bevestigen <span class="text-red-500">*</span></label>
                        <input
                            v-model="formulier.wachtwoord_bevestiging"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        />
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-3">
                        <input v-model="formulier.actief" id="actief" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-500" />
                        <label for="actief" class="text-sm font-semibold text-gray-700">Account direct activeren</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <Link href="/directeur/coordinatoren" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        {{ t('common.cancel') }}
                    </Link>
                    <button type="submit" :disabled="formulier.processing" class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50">
                        {{ formulier.processing ? t('common.loading') : t('common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </DirecteurLayout>
</template>
