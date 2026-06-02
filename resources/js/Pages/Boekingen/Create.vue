<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import SchipholLayout from '@/Layouts/SchipholLayout.vue';

const props = defineProps({
    vlucht: Object,
    stoelklasse: String,
    prijs: [String, Number],
});

const formulier = useForm({
    vlucht_id: props.vlucht.id,
    stoelklasse: props.stoelklasse,
    stoel_voorkeur: '',
    naam_reiziger: '',
    email_reiziger: '',
    telefoon_reiziger: '',
});

function naarBevestiging() {
    formulier.post(route('boekingen.bevestigen'));
}
</script>

<template>
    <Head title="Boeking aanmaken" />
    <SchipholLayout>
        <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Terug -->
            <Link :href="route('vluchten.show', vlucht.id)" class="mb-6 inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Terug naar vlucht
            </Link>

            <h1 class="mb-6 text-3xl font-bold text-gray-900">Boeking aanmaken</h1>

            <!-- Vlucht samenvatting -->
            <div class="mb-6 rounded-xl bg-blue-900 p-5 text-white">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-blue-200">{{ vlucht.luchtvaartmaatschappij }}</p>
                        <p class="text-xl font-bold">{{ vlucht.vlucht_nummer }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <p class="text-xl font-bold">{{ vlucht.vertrek_tijd.slice(11, 16) }}</p>
                            <p class="text-xs text-blue-200">{{ vlucht.vertrek_luchthaven.split('(')[0] }}</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-xs text-blue-200">{{ vlucht.duur }}</span>
                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-bold">{{ vlucht.aankomst_tijd.slice(11, 16) }}</p>
                            <p class="text-xs text-blue-200">{{ vlucht.aankomst_luchthaven.split('(')[0] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-blue-200">Stoelklasse</p>
                        <p class="rounded-full bg-yellow-400 px-3 py-1 text-sm font-bold text-blue-900 capitalize">
                            {{ stoelklasse }}
                        </p>
                        <p class="mt-1 text-xl font-bold">€{{ prijs }}</p>
                    </div>
                </div>
            </div>

            <!-- Boekingsformulier -->
            <form @submit.prevent="naarBevestiging" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900">Reizigersgegevens</h2>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">
                        Volledige naam <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="formulier.naam_reiziger"
                        type="text"
                        required
                        placeholder="Voornaam Achternaam"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        :class="{ 'border-red-500': formulier.errors.naam_reiziger }"
                    />
                    <p v-if="formulier.errors.naam_reiziger" class="mt-1 text-sm text-red-600">{{ formulier.errors.naam_reiziger }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">
                        E-mailadres <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="formulier.email_reiziger"
                        type="email"
                        required
                        placeholder="naam@voorbeeld.nl"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        :class="{ 'border-red-500': formulier.errors.email_reiziger }"
                    />
                    <p v-if="formulier.errors.email_reiziger" class="mt-1 text-sm text-red-600">{{ formulier.errors.email_reiziger }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Telefoonnummer</label>
                    <input
                        v-model="formulier.telefoon_reiziger"
                        type="tel"
                        placeholder="+31 6 12345678"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    />
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

                <div class="flex justify-end pt-2">
                    <button
                        type="submit"
                        :disabled="formulier.processing"
                        class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800 transition-colors disabled:opacity-50"
                    >
                        Naar kostenoverzicht →
                    </button>
                </div>
            </form>
        </div>
    </SchipholLayout>
</template>
