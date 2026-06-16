<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import SchipholLayout from '@/Layouts/SchipholLayout.vue'
import { useI18n } from '@/composables/useI18n.js'
import { reactive } from 'vue'

const { t } = useI18n()

const props = defineProps({
    resultaten: Array,
    zoekwaarden: Object,
})

const formulier = reactive({
    vertrek_luchthaven: props.zoekwaarden?.vertrek_luchthaven ?? '',
    aankomst_luchthaven: props.zoekwaarden?.aankomst_luchthaven ?? '',
    vertrek_datum: props.zoekwaarden?.vertrek_datum ?? '',
    stoelklasse: props.zoekwaarden?.stoelklasse ?? '',
    stoel_voorkeur: props.zoekwaarden?.stoel_voorkeur ?? '',
})

function zoeken() {
    router.get(route('vluchten.zoek'), formulier, { preserveScroll: false })
}

function statusKleur(status) {
    return {
        gepland: 'bg-green-100 text-green-800',
        vertrokken: 'bg-blue-100 text-blue-800',
        geland: 'bg-gray-100 text-gray-800',
        geannuleerd: 'bg-red-100 text-red-800',
    }[status] || 'bg-gray-100 text-gray-800'
}

const heeftGezocht = props.zoekwaarden?.vertrek_datum
</script>

<template>
    <Head :title="t('search.title')" />
    <SchipholLayout>
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ t('search.title') }}</h1>
                <p class="mt-2 text-gray-600">{{ t('search.subtitle') }}</p>
            </div>

            <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form @submit.prevent="zoeken" class="space-y-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('search.from') }}</label>
                            <input
                                v-model="formulier.vertrek_luchthaven"
                                type="text"
                                :placeholder="t('search.from')"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('search.to') }}</label>
                            <input
                                v-model="formulier.aankomst_luchthaven"
                                type="text"
                                :placeholder="t('search.to')"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            />
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('search.date') }}</label>
                            <input
                                v-model="formulier.vertrek_datum"
                                type="date"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('search.seatClass') }}</label>
                            <select
                                v-model="formulier.stoelklasse"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                                <option value="">{{ t('search.allClasses') }}</option>
                                <option value="economy">{{ t('flight.economy') }}</option>
                                <option value="business">{{ t('flight.business') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('search.seatPref') }}</label>
                            <select
                                v-model="formulier.stoel_voorkeur"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                                <option value="">{{ t('search.noPref') }}</option>
                                <option value="raam">{{ t('search.window') }}</option>
                                <option value="midden">{{ t('search.middle') }}</option>
                                <option value="gangpad">{{ t('search.aisle') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="flex items-center gap-2 rounded-lg bg-blue-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            {{ t('search.searchBtn') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results -->
            <div v-if="heeftGezocht">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">
                        <template v-if="resultaten?.length">
                            {{ resultaten.length }} {{ t('search.results') }}
                        </template>
                        <template v-else>
                            {{ t('search.noResults') }}
                        </template>
                    </h2>
                </div>

                <div v-if="!resultaten?.length" class="rounded-xl border border-gray-200 bg-white p-10 text-center text-gray-500">
                    <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="font-medium">{{ t('search.noResults') }}</p>
                    <p class="mt-1 text-sm">{{ t('search.noResultsSub') }}</p>
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="vlucht in resultaten"
                        :key="vlucht.id"
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow"
                    >
                        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex flex-1 items-center gap-6">
                                <div class="min-w-[100px]">
                                    <p class="text-xs text-gray-400">{{ t('flight.airline') }}</p>
                                    <p class="font-bold text-blue-900">{{ vlucht.luchtvaartmaatschappij }}</p>
                                    <p class="text-xs text-gray-500">{{ vlucht.vlucht_nummer }}</p>
                                </div>
                                <div class="flex flex-1 items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-xl font-bold">{{ vlucht.vertrek_tijd.slice(11, 16) }}</p>
                                        <p class="text-sm text-gray-600">{{ vlucht.vertrek_luchthaven.split('(')[0] }}</p>
                                    </div>
                                    <div class="flex flex-1 flex-col items-center">
                                        <p class="text-xs text-gray-400">{{ vlucht.duur }}</p>
                                        <div class="flex w-full items-center gap-1">
                                            <div class="h-px flex-1 bg-gray-300"></div>
                                            <svg class="h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                                            </svg>
                                            <div class="h-px flex-1 bg-gray-300"></div>
                                        </div>
                                        <p class="text-xs text-gray-400">{{ vlucht.vliegtuig_type }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xl font-bold">{{ vlucht.aankomst_tijd.slice(11, 16) }}</p>
                                        <p class="text-sm text-gray-600">{{ vlucht.aankomst_luchthaven.split('(')[0] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold', statusKleur(vlucht.status)]">
                                    {{ t('flight.statuses.' + vlucht.status) }}
                                </span>
                                <div class="flex gap-2">
                                    <div v-if="vlucht.prijs_economy" class="text-center rounded-lg bg-blue-50 px-3 py-1.5">
                                        <p class="text-xs font-semibold text-blue-700">Economy</p>
                                        <p class="font-bold text-blue-900">€{{ vlucht.prijs_economy }}</p>
                                    </div>
                                    <div v-if="vlucht.prijs_business" class="text-center rounded-lg bg-yellow-50 px-3 py-1.5">
                                        <p class="text-xs font-semibold text-yellow-700">Business</p>
                                        <p class="font-bold text-yellow-900">€{{ vlucht.prijs_business }}</p>
                                    </div>
                                </div>
                                <Link :href="route('vluchten.show', vlucht.id)" class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 transition-colors">
                                    {{ t('common.details') }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SchipholLayout>
</template>
