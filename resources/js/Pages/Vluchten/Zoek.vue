<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import SchipholLayout from '@/Layouts/SchipholLayout.vue';
import { reactive } from 'vue';

const props = defineProps({
    resultaten: Array,
    zoekwaarden: Object,
});

const formulier = reactive({
    vertrek_luchthaven: props.zoekwaarden?.vertrek_luchthaven ?? '',
    aankomst_luchthaven: props.zoekwaarden?.aankomst_luchthaven ?? '',
    vertrek_datum: props.zoekwaarden?.vertrek_datum ?? '',
    stoelklasse: props.zoekwaarden?.stoelklasse ?? '',
    stoel_voorkeur: props.zoekwaarden?.stoel_voorkeur ?? '',
});

function zoeken() {
    router.get(route('vluchten.zoek'), formulier, { preserveScroll: false });
}

function statusKleur(status) {
    const kleuren = {
        gepland: 'bg-green-100 text-green-800',
        vertrokken: 'bg-blue-100 text-blue-800',
        geland: 'bg-gray-100 text-gray-800',
        geannuleerd: 'bg-red-100 text-red-800',
    };
    return kleuren[status] || 'bg-gray-100 text-gray-800';
}

const heeftGezocht = props.zoekwaarden?.vertrek_datum;
</script>

<template>
    <Head title="Vlucht zoeken" />
    <SchipholLayout>
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Vlucht Zoeken</h1>
                <p class="mt-2 text-gray-600">Zoek op datum, bestemming en jouw stoelvoorkeur</p>
            </div>

            <!-- Zoekformulier -->
            <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form @submit.prevent="zoeken" class="space-y-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Vertrek luchthaven</label>
                            <input
                                v-model="formulier.vertrek_luchthaven"
                                type="text"
                                placeholder="bijv. Amsterdam"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Aankomst luchthaven</label>
                            <input
                                v-model="formulier.aankomst_luchthaven"
                                type="text"
                                placeholder="bijv. Londen, Barcelona"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Vertrekdatum <span class="text-red-500">*</span></label>
                        <input
                            v-model="formulier.vertrek_datum"
                            type="date"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:w-56"
                        />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Stoelklasse</label>
                            <select
                                v-model="formulier.stoelklasse"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                                <option value="">Alle klassen</option>
                                <option value="economy">Economy</option>
                                <option value="business">Business</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Stoelvoorkeur</label>
                            <select
                                v-model="formulier.stoel_voorkeur"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                                <option value="">Geen voorkeur</option>
                                <option value="raam">Raam</option>
                                <option value="midden">Midden</option>
                                <option value="gangpad">Gangpad</option>
                            </select>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="flex items-center gap-2 rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800 transition-colors"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Zoeken
                    </button>
                </form>
            </div>

            <!-- Zoekresultaten -->
            <div v-if="heeftGezocht">
                <h2 class="mb-4 text-xl font-bold text-gray-900">
                    Zoekresultaten
                    <span class="text-base font-normal text-gray-500">({{ resultaten.length }} gevonden)</span>
                </h2>

                <div v-if="resultaten.length === 0" class="rounded-xl border border-gray-200 bg-white p-10 text-center text-gray-500">
                    <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-medium">Geen vluchten gevonden voor deze zoekopdracht</p>
                    <p class="mt-1 text-sm">Probeer een andere datum of bestemming.</p>
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="vlucht in resultaten"
                        :key="vlucht.id"
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md"
                    >
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex flex-1 items-center gap-6">
                                <!-- Maatschappij -->
                                <div class="min-w-[110px]">
                                    <p class="text-xs text-gray-400">Maatschappij</p>
                                    <p class="font-semibold text-blue-900 text-sm">{{ vlucht.luchtvaartmaatschappij?.naam }}</p>
                                    <p class="text-xs text-gray-500">{{ vlucht.vlucht_nummer }}</p>
                                </div>

                                <!-- Route -->
                                <div class="flex flex-1 items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-xl font-bold">{{ vlucht.vertrek_tijd.slice(11, 16) }}</p>
                                        <p class="text-xs text-gray-500">{{ vlucht.vertrek_luchthaven.split('(')[0].trim() }}</p>
                                    </div>
                                    <div class="flex flex-1 flex-col items-center">
                                        <span class="text-xs text-gray-400">{{ vlucht.duur }}</span>
                                        <div class="flex w-full items-center gap-1">
                                            <div class="h-px flex-1 bg-gray-300"></div>
                                            <svg class="h-3 w-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                                            </svg>
                                            <div class="h-px flex-1 bg-gray-300"></div>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ vlucht.vliegtuig_type }}</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xl font-bold">{{ vlucht.aankomst_tijd.slice(11, 16) }}</p>
                                        <p class="text-xs text-gray-500">{{ vlucht.aankomst_luchthaven.split('(')[0].trim() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Beschikbaarheid & boeken -->
                            <div class="flex items-center gap-3">
                                <div v-if="!formulier.stoelklasse || formulier.stoelklasse === 'economy'" class="text-center">
                                    <p class="text-xs font-semibold uppercase text-blue-700">Economy</p>
                                    <p class="font-bold text-blue-900">€{{ vlucht.prijs_economy }}</p>
                                    <p class="text-xs text-gray-500">{{ vlucht.beschikbaar_economy }} vrij</p>
                                </div>
                                <div v-if="!formulier.stoelklasse || formulier.stoelklasse === 'business'" class="text-center">
                                    <p class="text-xs font-semibold uppercase text-yellow-700">Business</p>
                                    <p class="font-bold text-yellow-900">€{{ vlucht.prijs_business }}</p>
                                    <p class="text-xs text-gray-500">{{ vlucht.beschikbaar_business }} vrij</p>
                                </div>
                                <Link
                                    :href="route('vluchten.show', vlucht.id)"
                                    class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
                                >
                                    Selecteer
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SchipholLayout>
</template>
