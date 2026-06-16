<script setup>
import { Head, Link } from '@inertiajs/vue3'
import SchipholLayout from '@/Layouts/SchipholLayout.vue'
import { useI18n } from '@/composables/useI18n.js'

const { t } = useI18n()

defineProps({ boeking: Object })

function afdrukken() {
    window.print()
}
</script>

<template>
    <Head :title="`${t('booking.number')} ${boeking.boekings_nummer}`" />

    <style>
        @media print {
            .geen-print { display: none !important; }
            body { background: white !important; }
            .print-kaart { box-shadow: none !important; border: 1px solid #ccc !important; }
        }
    </style>

    <SchipholLayout>
        <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="geen-print mb-6 flex items-center justify-between">
                <Link :href="route('vluchten.index')" class="inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ t('booking.backToFlights') }}
                </Link>
                <button
                    @click="afdrukken"
                    class="flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    {{ t('booking.print') }}
                </button>
            </div>

            <div class="geen-print mb-6 flex items-center gap-3 rounded-xl bg-green-50 border border-green-200 p-4">
                <svg class="h-8 w-8 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-bold text-green-800">{{ t('booking.confirmed') }}</p>
                    <p class="text-sm text-green-700">{{ t('booking.confirmSent') }} {{ boeking.email_reiziger }}</p>
                </div>
            </div>

            <div class="print-kaart overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="bg-blue-900 p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                                </svg>
                                <span class="font-semibold text-lg">Schiphol Vluchtbeheer</span>
                            </div>
                            <p class="text-blue-200 text-sm">{{ t('booking.receipt') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-blue-300">{{ t('booking.number') }}</p>
                            <p class="text-2xl font-bold tracking-widest">{{ boeking.boekings_nummer }}</p>
                            <span :class="['mt-1 inline-block rounded-full px-3 py-0.5 text-xs font-bold uppercase',
                                boeking.status === 'bevestigd' ? 'bg-green-100 text-green-800' :
                                boeking.status === 'geannuleerd' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                            ]">
                                {{ t('booking.status.' + boeking.status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="mb-6 flex items-center gap-6">
                        <div>
                            <p class="text-3xl font-bold text-gray-900">{{ boeking.vlucht.vertrek_tijd.slice(11, 16) }}</p>
                            <p class="text-lg font-semibold text-gray-700">{{ boeking.vlucht.vertrek_luchthaven.split('(')[0].trim() }}</p>
                            <p class="text-sm text-gray-500">{{ boeking.vlucht.vertrek_tijd.slice(0, 10) }}</p>
                        </div>
                        <div class="flex flex-1 flex-col items-center">
                            <span class="text-sm text-gray-500">{{ boeking.vlucht.duur }}</span>
                            <div class="flex w-full items-center gap-2">
                                <div class="h-0.5 flex-1 bg-gray-300"></div>
                                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                                </svg>
                                <div class="h-0.5 flex-1 bg-gray-300"></div>
                            </div>
                            <span class="text-xs text-gray-400">{{ boeking.vlucht.vliegtuig_type }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-900">{{ boeking.vlucht.aankomst_tijd.slice(11, 16) }}</p>
                            <p class="text-lg font-semibold text-gray-700">{{ boeking.vlucht.aankomst_luchthaven.split('(')[0].trim() }}</p>
                            <p class="text-sm text-gray-500">{{ boeking.vlucht.aankomst_tijd.slice(0, 10) }}</p>
                        </div>
                    </div>

                    <hr class="my-5 border-gray-200" />

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">{{ t('booking.passenger') }}</h3>
                            <p class="font-bold text-gray-900 text-lg">{{ boeking.naam_reiziger }}</p>
                            <p class="text-sm text-gray-600">{{ boeking.email_reiziger }}</p>
                            <p v-if="boeking.telefoon_reiziger" class="text-sm text-gray-600">{{ boeking.telefoon_reiziger }}</p>
                        </div>
                        <div>
                            <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">{{ t('booking.flightDetails') }}</h3>
                            <dl class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">{{ t('flight.number') }}</dt>
                                    <dd class="font-semibold">{{ boeking.vlucht.vlucht_nummer }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">{{ t('flight.airline') }}</dt>
                                    <dd class="font-semibold">{{ boeking.vlucht.luchtvaartmaatschappij }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">{{ t('booking.seatClass') }}</dt>
                                    <dd class="font-semibold capitalize">{{ boeking.stoelklasse }}</dd>
                                </div>
                                <div v-if="boeking.stoel_voorkeur" class="flex justify-between">
                                    <dt class="text-gray-500">{{ t('booking.seatPrefLabel') }}</dt>
                                    <dd class="font-semibold capitalize">{{ boeking.stoel_voorkeur }}</dd>
                                </div>
                                <div v-if="boeking.vlucht.gate" class="flex justify-between">
                                    <dt class="text-gray-500">{{ t('flight.gate') }}</dt>
                                    <dd class="font-semibold">{{ boeking.vlucht.gate.terminal }}{{ boeking.vlucht.gate.nummer }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <hr class="my-5 border-gray-200" />

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400">{{ t('booking.bookedOn') }}</p>
                            <p class="text-sm text-gray-600">{{ boeking.aangemaakt_op }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">{{ t('booking.totalPrice') }}</p>
                            <p class="text-3xl font-bold text-blue-900">€{{ boeking.prijs }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SchipholLayout>
</template>
