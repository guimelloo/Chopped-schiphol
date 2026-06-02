<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import SchipholLayout from '@/Layouts/SchipholLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    vluchten: Array,
    actieveKlasse: String,
});

const klasse = ref(props.actieveKlasse || 'alle');

function filterOp(k) {
    klasse.value = k;
    router.get(route('vluchten.index'), { klasse: k }, { preserveScroll: true, replace: true });
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
</script>

<template>
    <Head title="Vluchtoverzicht" />
    <SchipholLayout>
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Koptekst -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Beschikbare Vluchten</h1>
                <p class="mt-2 text-gray-600">Overzicht van alle vluchten vertrekkend vanuit Amsterdam Schiphol</p>
            </div>

            <!-- Zoekknop -->
            <div class="mb-6 flex flex-wrap items-center gap-4">
                <Link
                    :href="route('vluchten.zoek')"
                    class="flex items-center gap-2 rounded-lg bg-yellow-400 px-5 py-2.5 font-semibold text-blue-900 shadow hover:bg-yellow-300 transition-colors"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Vlucht zoeken
                </Link>

                <!-- Klasse filter -->
                <div class="flex overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                    <button
                        v-for="(label, k) in { alle: 'Alle klassen', economy: 'Economy', business: 'Business' }"
                        :key="k"
                        @click="filterOp(k)"
                        :class="[
                            'px-4 py-2 text-sm font-medium transition-colors',
                            klasse === k
                                ? 'bg-blue-900 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-50',
                        ]"
                    >
                        {{ label }}
                    </button>
                </div>
            </div>

            <!-- Vluchtlijst -->
            <div v-if="vluchten.length === 0" class="rounded-lg border border-gray-200 bg-white p-12 text-center text-gray-500">
                <svg class="mx-auto mb-4 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <p class="text-lg font-medium">Geen vluchten gevonden</p>
                <p class="mt-1 text-sm">Probeer een andere filter of zoek op datum.</p>
            </div>

            <div v-else class="space-y-4">
                <div
                    v-for="vlucht in vluchten"
                    :key="vlucht.id"
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md"
                >
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <!-- Vluchtinfo -->
                        <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center sm:gap-6">
                            <!-- Maatschappij -->
                            <div class="flex min-w-[120px] flex-col">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Maatschappij</span>
                                <span class="font-semibold text-blue-900">
                                    {{ vlucht.luchtvaartmaatschappij?.naam ?? '—' }}
                                </span>
                                <span class="text-xs text-gray-500">{{ vlucht.vlucht_nummer }}</span>
                            </div>

                            <!-- Route -->
                            <div class="flex flex-1 items-center gap-3">
                                <div class="text-center">
                                    <p class="text-xl font-bold text-gray-900">{{ vlucht.vertrek_tijd.slice(11, 16) }}</p>
                                    <p class="text-sm font-medium text-gray-600">{{ vlucht.vertrek_luchthaven.split('(')[0].trim() }}</p>
                                    <p class="text-xs text-gray-400">{{ vlucht.vertrek_tijd.slice(0, 10) }}</p>
                                </div>
                                <div class="flex flex-1 flex-col items-center">
                                    <p class="text-xs text-gray-400">{{ vlucht.duur }}</p>
                                    <div class="relative flex w-full items-center">
                                        <div class="h-px flex-1 bg-gray-300"></div>
                                        <svg class="h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                                        </svg>
                                        <div class="h-px flex-1 bg-gray-300"></div>
                                    </div>
                                    <p class="text-xs text-gray-400">{{ vlucht.vliegtuig_type }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xl font-bold text-gray-900">{{ vlucht.aankomst_tijd.slice(11, 16) }}</p>
                                    <p class="text-sm font-medium text-gray-600">{{ vlucht.aankomst_luchthaven.split('(')[0].trim() }}</p>
                                    <p class="text-xs text-gray-400">{{ vlucht.aankomst_tijd.slice(0, 10) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Klassen & prijzen -->
                        <div class="flex flex-row gap-3 sm:flex-col sm:items-end">
                            <div class="flex gap-3">
                                <div class="rounded-lg bg-blue-50 px-3 py-2 text-center">
                                    <p class="text-xs font-semibold uppercase text-blue-700">Economy</p>
                                    <p class="text-lg font-bold text-blue-900">€{{ vlucht.prijs_economy }}</p>
                                    <p class="text-xs text-gray-500">{{ vlucht.beschikbaar_economy }} stoelen</p>
                                </div>
                                <div class="rounded-lg bg-yellow-50 px-3 py-2 text-center">
                                    <p class="text-xs font-semibold uppercase text-yellow-700">Business</p>
                                    <p class="text-lg font-bold text-yellow-900">€{{ vlucht.prijs_business }}</p>
                                    <p class="text-xs text-gray-500">{{ vlucht.beschikbaar_business }} stoelen</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold', statusKleur(vlucht.status)]">
                                    {{ vlucht.status }}
                                </span>
                                <Link
                                    :href="route('vluchten.show', vlucht.id)"
                                    class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
                                >
                                    Details
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SchipholLayout>
</template>
