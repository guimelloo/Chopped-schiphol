<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SchipholLayout from '@/Layouts/SchipholLayout.vue';

defineProps({
    vlucht: Object,
});

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
    <Head :title="`Vlucht ${vlucht.vlucht_nummer}`" />
    <SchipholLayout>
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Terug -->
            <Link :href="route('vluchten.index')" class="mb-6 inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Terug naar overzicht
            </Link>

            <!-- Vlucht koptekst -->
            <div class="mb-6 rounded-xl bg-blue-900 p-6 text-white shadow-lg">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="text-3xl font-bold">{{ vlucht.vlucht_nummer }}</span>
                            <span :class="['rounded-full px-3 py-1 text-xs font-bold uppercase', statusKleur(vlucht.status)]">
                                {{ vlucht.status }}
                            </span>
                        </div>
                        <p class="mt-1 text-blue-200">{{ vlucht.luchtvaartmaatschappij?.naam }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-200">Vliegtuigtype</p>
                        <p class="text-lg font-semibold">{{ vlucht.vliegtuig_type }}</p>
                    </div>
                </div>

                <!-- Route tijdlijn -->
                <div class="mt-6 flex items-center gap-4">
                    <div>
                        <p class="text-3xl font-bold">{{ vlucht.vertrek_tijd.slice(11, 16) }}</p>
                        <p class="text-lg font-semibold text-yellow-300">{{ vlucht.vertrek_luchthaven }}</p>
                        <p class="text-sm text-blue-200">{{ vlucht.vertrek_tijd.slice(0, 10) }}</p>
                    </div>
                    <div class="flex flex-1 flex-col items-center">
                        <span class="text-sm text-blue-200">{{ vlucht.duur }}</span>
                        <div class="mt-1 flex w-full items-center gap-1">
                            <div class="h-0.5 flex-1 bg-blue-400"></div>
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                            <div class="h-0.5 flex-1 bg-blue-400"></div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold">{{ vlucht.aankomst_tijd.slice(11, 16) }}</p>
                        <p class="text-lg font-semibold text-yellow-300">{{ vlucht.aankomst_luchthaven }}</p>
                        <p class="text-sm text-blue-200">{{ vlucht.aankomst_tijd.slice(0, 10) }}</p>
                    </div>
                </div>

                <!-- Gate info -->
                <div v-if="vlucht.gate" class="mt-4 flex items-center gap-2 rounded-lg bg-blue-800 px-4 py-2">
                    <svg class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    <span class="text-sm">
                        <span class="font-semibold">Gate {{ vlucht.gate.nummer }}</span>
                        — Terminal {{ vlucht.gate.terminal }}
                    </span>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Stoelklassen -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-bold text-gray-900">Stoelklassen &amp; Beschikbaarheid</h2>

                    <!-- Economy -->
                    <div class="mb-4 rounded-lg bg-blue-50 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-blue-900">Economy Class</h3>
                                <p class="text-sm text-gray-600">Standaard stoel, inclusief handbagage</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-900">€{{ vlucht.prijs_economy }}</p>
                                <p class="text-xs text-gray-500">per persoon</p>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div :class="['h-2.5 w-2.5 rounded-full', vlucht.beschikbaar_economy > 0 ? 'bg-green-500' : 'bg-red-500']"></div>
                                <span class="text-sm text-gray-700">
                                    {{ vlucht.beschikbaar_economy > 0 ? `${vlucht.beschikbaar_economy} stoelen beschikbaar` : 'Uitverkocht' }}
                                </span>
                            </div>
                            <Link
                                v-if="vlucht.beschikbaar_economy > 0"
                                :href="route('boekingen.create', { vlucht: vlucht.id, klasse: 'economy' })"
                                class="rounded-lg bg-blue-900 px-4 py-1.5 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
                            >
                                Boek economy
                            </Link>
                        </div>
                    </div>

                    <!-- Business -->
                    <div class="rounded-lg bg-yellow-50 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-yellow-900">Business Class</h3>
                                <p class="text-sm text-gray-600">Premium stoel, lounge toegang</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-yellow-900">€{{ vlucht.prijs_business }}</p>
                                <p class="text-xs text-gray-500">per persoon</p>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div :class="['h-2.5 w-2.5 rounded-full', vlucht.beschikbaar_business > 0 ? 'bg-green-500' : 'bg-red-500']"></div>
                                <span class="text-sm text-gray-700">
                                    {{ vlucht.beschikbaar_business > 0 ? `${vlucht.beschikbaar_business} stoelen beschikbaar` : 'Uitverkocht' }}
                                </span>
                            </div>
                            <Link
                                v-if="vlucht.beschikbaar_business > 0"
                                :href="route('boekingen.create', { vlucht: vlucht.id, klasse: 'business' })"
                                class="rounded-lg bg-yellow-500 px-4 py-1.5 text-sm font-semibold text-white hover:bg-yellow-600 transition-colors"
                            >
                                Boek business
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Services & details -->
                <div class="space-y-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-4 text-lg font-bold text-gray-900">Inbegrepen Services</h2>
                        <ul v-if="vlucht.services?.length" class="space-y-2">
                            <li
                                v-for="service in vlucht.services"
                                :key="service"
                                class="flex items-center gap-3 text-gray-700"
                            >
                                <svg class="h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ service }}
                            </li>
                        </ul>
                        <p v-else class="text-gray-500">Geen extra services</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-4 text-lg font-bold text-gray-900">Vluchtdetails</h2>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Vluchtnummer</dt>
                                <dd class="font-semibold text-gray-900">{{ vlucht.vlucht_nummer }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Vliegtuigtype</dt>
                                <dd class="font-semibold text-gray-900">{{ vlucht.vliegtuig_type }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Vluchtduur</dt>
                                <dd class="font-semibold text-gray-900">{{ vlucht.duur }}</dd>
                            </div>
                            <div v-if="vlucht.gate" class="flex justify-between">
                                <dt class="text-gray-500">Gate</dt>
                                <dd class="font-semibold text-gray-900">{{ vlucht.gate.nummer }} (Terminal {{ vlucht.gate.terminal }})</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Status</dt>
                                <dd>
                                    <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold', statusKleur(vlucht.status)]">
                                        {{ vlucht.status }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </SchipholLayout>
</template>
