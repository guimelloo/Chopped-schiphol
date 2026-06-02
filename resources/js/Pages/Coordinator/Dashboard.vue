<script setup>
import { Head, router } from '@inertiajs/vue3';
import CoordinatorLayout from '@/Layouts/CoordinatorLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    coordinator: Object,
    maatschappijen: Array,
    dagvluchten: Array,
    datum: String,
    weekStart: String,
    weekEind: String,
});

const datum = ref(props.datum);
const activeMaatschappij = ref(null);

function navigeerDatum(d) {
    datum.value = d;
    router.get(route('coordinator.dashboard'), { datum: d }, { preserveScroll: true, replace: true });
}

function vorigeDag() {
    const d = new Date(datum.value);
    d.setDate(d.getDate() - 1);
    navigeerDatum(d.toISOString().slice(0, 10));
}

function volgensDag() {
    const d = new Date(datum.value);
    d.setDate(d.getDate() + 1);
    navigeerDatum(d.toISOString().slice(0, 10));
}

function vandaag() {
    const now = new Date();
    navigeerDatum(now.toISOString().slice(0, 10));
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

function formatDatum(d) {
    return new Date(d + 'T00:00:00').toLocaleDateString('nl-NL', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
    });
}
</script>

<template>
    <Head title="Coördinator Dashboard" />
    <CoordinatorLayout>
        <!-- Welkomstbanner -->
        <div class="mb-8 rounded-xl bg-blue-900 p-6 text-white shadow">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Welkom, {{ coordinator.naam }}</h1>
                    <p class="mt-1 text-blue-200">Coördinator Dashboard — Amsterdam Schiphol</p>
                </div>
                <div class="text-right text-sm text-blue-200">
                    <p>Week: {{ weekStart }} t/m {{ weekEind }}</p>
                    <p class="font-semibold text-white">{{ maatschappijen.length }} maatschappijen actief</p>
                </div>
            </div>
        </div>

        <!-- Dagschema -->
        <div class="mb-8">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-bold text-gray-900">Dagschema</h2>
                <div class="flex items-center gap-2">
                    <button @click="vorigeDag" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium hover:bg-gray-50 transition-colors">
                        ← Vorige dag
                    </button>
                    <button @click="vandaag" class="rounded-lg border border-blue-900 bg-blue-900 px-3 py-2 text-sm font-medium text-white hover:bg-blue-800 transition-colors">
                        Vandaag
                    </button>
                    <input
                        :value="datum"
                        type="date"
                        @change="navigeerDatum($event.target.value)"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    />
                    <button @click="volgensDag" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium hover:bg-gray-50 transition-colors">
                        Volgende dag →
                    </button>
                </div>
            </div>

            <p class="mb-4 text-sm text-gray-600 capitalize">{{ formatDatum(datum) }}</p>

            <div v-if="dagvluchten.length === 0" class="rounded-xl border border-gray-200 bg-white p-10 text-center text-gray-500">
                <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="font-medium">Geen vluchten gepland op deze dag</p>
            </div>

            <div v-else class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tijd</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Vlucht</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Maatschappij</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Route</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aankomst</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="vlucht in dagvluchten" :key="vlucht.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-bold text-blue-900">{{ vlucht.vertrek_tijd }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ vlucht.vlucht_nummer }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ vlucht.luchtvaartmaatschappij }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ vlucht.vertrek_luchthaven.split('(')[0].trim() }}
                                <span class="text-gray-400">→</span>
                                {{ vlucht.aankomst_luchthaven.split('(')[0].trim() }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ vlucht.aankomst_tijd }}</td>
                            <td class="px-4 py-3">
                                <span :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold', statusKleur(vlucht.status)]">
                                    {{ vlucht.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Overzicht per maatschappij -->
        <div>
            <h2 class="mb-4 text-xl font-bold text-gray-900">
                Overzicht Luchtvaartmaatschappijen
                <span class="text-sm font-normal text-gray-500">(weekschema {{ weekStart }} – {{ weekEind }})</span>
            </h2>

            <div class="space-y-4">
                <div
                    v-for="maatschappij in maatschappijen"
                    :key="maatschappij.id"
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                >
                    <!-- Maatschappij header (klikbaar) -->
                    <button
                        @click="activeMaatschappij = activeMaatschappij === maatschappij.id ? null : maatschappij.id"
                        class="flex w-full items-center justify-between px-6 py-4 text-left hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-900 text-white font-bold text-sm">
                                {{ maatschappij.iata_code }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ maatschappij.naam }}</p>
                                <p class="text-sm text-gray-500">{{ maatschappij.land }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800">
                                {{ maatschappij.vluchten.length }} vluchten deze week
                            </span>
                            <svg
                                :class="['h-5 w-5 text-gray-400 transition-transform', activeMaatschappij === maatschappij.id ? 'rotate-180' : '']"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <!-- Vluchten van deze maatschappij (uitklapbaar) -->
                    <div v-if="activeMaatschappij === maatschappij.id">
                        <div v-if="maatschappij.vluchten.length === 0" class="border-t border-gray-100 px-6 py-8 text-center text-sm text-gray-500">
                            Geen vluchten gepland in de geselecteerde week
                        </div>
                        <table v-else class="min-w-full divide-y divide-gray-100 border-t border-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Datum</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Vlucht</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Route</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Vertrek</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aankomst</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Vliegtuig</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Gate</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr v-for="v in maatschappij.vluchten" :key="v.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-xs text-gray-500">{{ v.vertrek_tijd.slice(0, 10) }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-blue-900">{{ v.vlucht_nummer }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ v.vertrek_luchthaven.split('(')[0].trim() }} → {{ v.aankomst_luchthaven.split('(')[0].trim() }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold">{{ v.vertrek_tijd.slice(11, 16) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ v.aankomst_tijd.slice(11, 16) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ v.vliegtuig_type }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-700">{{ v.gate }}</td>
                                    <td class="px-4 py-3">
                                        <span :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold', statusKleur(v.status)]">
                                            {{ v.status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </CoordinatorLayout>
</template>
