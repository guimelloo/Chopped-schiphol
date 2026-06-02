<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import SchipholLayout from '@/Layouts/SchipholLayout.vue';

const props = defineProps({
    data: Object,
    prijs: [String, Number],
    vlucht: Object,
});

const btw     = (parseFloat(props.prijs) * 0.21).toFixed(2);
const totaal  = parseFloat(props.prijs).toFixed(2);
const netto   = (parseFloat(props.prijs) - parseFloat(btw)).toFixed(2);

const formulier = useForm({ ...props.data });

function bevestigen() {
    formulier.post(route('boekingen.store'));
}

const voorkeurLabel = { raam: 'Raam', midden: 'Midden', gangpad: 'Gangpad' };
</script>

<template>
    <Head title="Boeking bevestigen" />
    <SchipholLayout>
        <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="mb-2 text-3xl font-bold text-gray-900">Boeking bevestigen</h1>
            <p class="mb-8 text-gray-600">Controleer uw gegevens en bevestig de boeking.</p>

            <!-- Stap indicator -->
            <div class="mb-8 flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white text-sm font-bold">✓</div>
                    <span class="text-sm font-medium text-gray-700">Gegevens</span>
                </div>
                <div class="h-px flex-1 bg-gray-300"></div>
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white text-sm font-bold">2</div>
                    <span class="text-sm font-semibold text-blue-900">Bevestigen</span>
                </div>
                <div class="h-px flex-1 bg-gray-300"></div>
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-500 text-sm font-bold">3</div>
                    <span class="text-sm text-gray-500">Klaar</span>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Vluchtgegevens -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-bold text-gray-900">Vluchtgegevens</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Vluchtnummer</dt>
                            <dd class="font-semibold">{{ vlucht.vlucht_nummer }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Maatschappij</dt>
                            <dd class="font-semibold">{{ vlucht.luchtvaartmaatschappij }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 mb-1">Route</dt>
                            <dd class="font-semibold">{{ vlucht.vertrek_luchthaven }}</dd>
                            <dd class="font-semibold text-blue-900">→ {{ vlucht.aankomst_luchthaven }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Vertrek</dt>
                            <dd class="font-semibold">{{ vlucht.vertrek_tijd }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Aankomst</dt>
                            <dd class="font-semibold">{{ vlucht.aankomst_tijd }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Stoelklasse</dt>
                            <dd>
                                <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 capitalize">
                                    {{ data.stoelklasse }}
                                </span>
                            </dd>
                        </div>
                        <div v-if="data.stoel_voorkeur" class="flex justify-between">
                            <dt class="text-gray-500">Stoelvoorkeur</dt>
                            <dd class="font-semibold">{{ voorkeurLabel[data.stoel_voorkeur] }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Reizigersgegevens -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-bold text-gray-900">Reizigersgegevens</h2>
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-gray-500">Naam</dt>
                            <dd class="font-semibold">{{ data.naam_reiziger }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">E-mailadres</dt>
                            <dd class="font-semibold">{{ data.email_reiziger }}</dd>
                        </div>
                        <div v-if="data.telefoon_reiziger">
                            <dt class="text-gray-500">Telefoonnummer</dt>
                            <dd class="font-semibold">{{ data.telefoon_reiziger }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Kostenoverzicht -->
            <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Kostenoverzicht</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Ticketprijs (excl. BTW)</span>
                        <span>€{{ netto }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>BTW (21%)</span>
                        <span>€{{ btw }}</span>
                    </div>
                    <div class="my-2 border-t border-gray-200 pt-2 flex justify-between text-lg font-bold text-gray-900">
                        <span>Totaal</span>
                        <span class="text-blue-900">€{{ totaal }}</span>
                    </div>
                </div>
            </div>

            <!-- Bevestig knop -->
            <div class="mt-6 flex justify-between">
                <button
                    type="button"
                    onclick="history.back()"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
                >
                    ← Wijzigen
                </button>
                <button
                    @click="bevestigen"
                    :disabled="formulier.processing"
                    class="rounded-lg bg-green-600 px-8 py-3 font-bold text-white hover:bg-green-700 transition-colors disabled:opacity-50"
                >
                    Boeking bevestigen
                </button>
            </div>
        </div>
    </SchipholLayout>
</template>
