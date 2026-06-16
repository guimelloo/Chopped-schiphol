<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import SchipholLayout from '@/Layouts/SchipholLayout.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref, computed } from 'vue'

const { t } = useI18n()

const props = defineProps({
    data: Object,
    prijs: [String, Number],
    vlucht: Object,
})

const btw    = (parseFloat(props.prijs) * 0.21).toFixed(2)
const totaal = parseFloat(props.prijs).toFixed(2)
const netto  = (parseFloat(props.prijs) - parseFloat(btw)).toFixed(2)

const TESTKAART = {
    kaart_naam:        'TEST GEBRUIKER',
    kaart_nummer:      '4242424242424242',
    kaart_vervaldatum: '12/28',
    kaart_cvv:         '123',
}

const formulier = useForm({
    vlucht_id:         props.data.vlucht_id,
    stoelklasse:       props.data.stoelklasse,
    stoel_voorkeur:    props.data.stoel_voorkeur,
    naam_reiziger:     props.data.naam_reiziger,
    email_reiziger:    props.data.email_reiziger,
    telefoon_reiziger: props.data.telefoon_reiziger,
    kaart_naam:        '',
    kaart_nummer:      '',
    kaart_vervaldatum: '',
    kaart_cvv:         '',
})

function vulTestkaartIn() {
    formulier.kaart_naam        = TESTKAART.kaart_naam
    formulier.kaart_nummer      = TESTKAART.kaart_nummer
    formulier.kaart_vervaldatum = TESTKAART.kaart_vervaldatum
    formulier.kaart_cvv         = TESTKAART.kaart_cvv
    kaartNummerGeformat.value   = '4242 4242 4242 4242'
    vervalGeformat.value        = '12/28'
}

const kaartNummerGeformat = ref('')
const kaartType = computed(() => {
    const n = formulier.kaart_nummer
    if (n.startsWith('4')) return 'visa'
    if (n.startsWith('5')) return 'mastercard'
    if (n.startsWith('3')) return 'amex'
    return 'generic'
})

function formatKaartNummer(event) {
    const raw   = event.target.value.replace(/\D/g, '').slice(0, 16)
    kaartNummerGeformat.value = raw.replace(/(.{4})/g, '$1 ').trim()
    formulier.kaart_nummer = raw
}

const vervalGeformat = ref('')
function formatVerval(event) {
    const raw = event.target.value.replace(/\D/g, '').slice(0, 4)
    vervalGeformat.value = raw.length >= 3 ? raw.slice(0, 2) + '/' + raw.slice(2) : raw
    formulier.kaart_vervaldatum = vervalGeformat.value
}

const isGeldig = computed(() =>
    formulier.kaart_naam.trim().length > 2 &&
    formulier.kaart_nummer.length === 16 &&
    formulier.kaart_vervaldatum.length === 5 &&
    formulier.kaart_cvv.length >= 3
)

function betalen() {
    formulier.post(route('boekingen.store'))
}

const kaartDisplayNummer = computed(() => {
    const raw = formulier.kaart_nummer.padEnd(16, '•')
    return [raw.slice(0,4), raw.slice(4,8), raw.slice(8,12), raw.slice(12,16)].join(' ')
})
</script>

<template>
    <Head title="Betaling" />
    <SchipholLayout>
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Testmodus banner -->
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-amber-300 bg-amber-50 px-5 py-4">
                <span class="shrink-0 rounded-md bg-amber-400 px-2 py-0.5 text-xs font-black uppercase tracking-widest text-white">Testmodus</span>
                <p class="text-sm text-amber-800">
                    Dit is een <strong>gesimuleerde betaling</strong> — er wordt geen echt geld afgeschreven. Gebruik uitsluitend de testkaartgegevens.
                </p>
            </div>

            <h1 class="mb-2 text-3xl font-bold text-gray-900">{{ t('booking.payment') }}</h1>
            <p class="mb-8 text-gray-600">Vul de testkaartgegevens in om de boeking te voltooien.</p>

            <!-- Step indicator -->
            <div class="mb-8 flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white text-sm font-bold">✓</div>
                    <span class="text-sm font-medium text-gray-700">{{ t('booking.step1') }}</span>
                </div>
                <div class="h-px flex-1 bg-gray-300"></div>
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white text-sm font-bold">✓</div>
                    <span class="text-sm font-medium text-gray-700">{{ t('booking.step2') }}</span>
                </div>
                <div class="h-px flex-1 bg-gray-300"></div>
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white text-sm font-bold">3</div>
                    <span class="text-sm font-semibold text-blue-900">{{ t('booking.step3') }}</span>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-5">
                <!-- Overzicht (left) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <h2 class="mb-3 text-sm font-bold uppercase tracking-wider text-gray-400">Boeking overzicht</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Vlucht</span>
                                <span class="font-semibold">{{ vlucht.vlucht_nummer }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Maatschappij</span>
                                <span class="font-semibold">{{ vlucht.luchtvaartmaatschappij }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Route</span>
                                <p class="font-semibold mt-0.5">{{ vlucht.vertrek_luchthaven.split('(')[0].trim() }}</p>
                                <p class="text-blue-900 font-semibold">→ {{ vlucht.aankomst_luchthaven.split('(')[0].trim() }}</p>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Vertrek</span>
                                <span class="font-semibold">{{ vlucht.vertrek_tijd.slice(0, 16) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Klasse</span>
                                <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 capitalize">
                                    {{ data.stoelklasse }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Reiziger</span>
                                <span class="font-semibold">{{ data.naam_reiziger }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <h2 class="mb-3 text-sm font-bold uppercase tracking-wider text-gray-400">Totaal</h2>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between text-gray-500">
                                <span>Ticketprijs</span><span>€{{ netto }}</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>BTW (21%)</span><span>€{{ btw }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-2 text-lg font-bold text-gray-900">
                                <span>Totaal</span>
                                <span class="text-blue-900">€{{ totaal }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Test info -->
                    <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 text-xs text-amber-800">
                        <p class="font-bold mb-2 text-sm">Testkaartgegevens</p>
                        <div class="space-y-1 mb-3 font-mono">
                            <p>Nr: <strong>4242 4242 4242 4242</strong></p>
                            <p>Vervaldatum: <strong>12/28</strong></p>
                            <p>CVV: <strong>123</strong></p>
                        </div>
                        <button
                            type="button"
                            @click="vulTestkaartIn"
                            class="w-full rounded-lg bg-amber-400 py-2 text-xs font-bold text-white hover:bg-amber-500 transition-colors"
                        >
                            Vul testkaart automatisch in
                        </button>
                    </div>
                </div>

                <!-- Betaalformulier (right) -->
                <div class="lg:col-span-3">
                    <!-- Card preview -->
                    <div class="mb-5 h-44 w-full rounded-2xl bg-gradient-to-br from-blue-900 to-blue-700 p-6 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute right-4 top-4 opacity-20">
                            <svg class="h-20 w-20" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="8" cy="12" r="6"/><circle cx="16" cy="12" r="6" opacity=".5"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="h-8 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <rect width="24" height="16" rx="2" y="4"/>
                            </svg>
                            <span class="text-xs font-bold tracking-widest uppercase text-blue-200">
                                {{ kaartType === 'visa' ? 'VISA' : kaartType === 'mastercard' ? 'MASTERCARD' : 'BETAALKAART' }}
                            </span>
                        </div>
                        <p class="text-xl font-mono tracking-widest mb-4">{{ kaartDisplayNummer }}</p>
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-xs text-blue-300 uppercase tracking-wider">Kaarthouder</p>
                                <p class="font-semibold uppercase tracking-wide text-sm">
                                    {{ formulier.kaart_naam || 'NAAM KAARTHOUDER' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-blue-300 uppercase tracking-wider">Geldig t/m</p>
                                <p class="font-semibold font-mono">{{ vervalGeformat || 'MM/YY' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="betalen" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-bold text-gray-900">Kaartgegevens</h2>
                            <span class="rounded-full bg-amber-100 px-3 py-0.5 text-xs font-bold text-amber-700 uppercase tracking-wider">Testmodus</span>
                        </div>

                        <div v-if="formulier.errors.kaart_nummer" class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                            {{ formulier.errors.kaart_nummer }}
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">
                                Naam kaarthouder <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="formulier.kaart_naam"
                                type="text"
                                required
                                placeholder="Voornaam Achternaam"
                                autocomplete="cc-name"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 uppercase"
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">
                                Kaartnummer <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    :value="kaartNummerGeformat"
                                    @input="formatKaartNummer"
                                    type="text"
                                    required
                                    placeholder="0000 0000 0000 0000"
                                    autocomplete="cc-number"
                                    maxlength="19"
                                    inputmode="numeric"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-12 text-sm font-mono tracking-widest focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                />
                                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg v-if="kaartType === 'visa'" class="h-6 w-10 text-blue-800" viewBox="0 0 780 500" fill="currentColor">
                                        <path d="M293.2 348.7l33.4-195.6h53.4l-33.4 195.6z"/>
                                        <path d="M491.3 157.7c-10.6-3.9-27.2-8.1-47.9-8.1-52.8 0-89.9 26.5-90.2 64.4-.3 28 26.4 43.6 46.6 52.9 20.7 9.5 27.7 15.6 27.6 24.1-.1 13-16.6 18.9-31.9 18.9-21.3 0-32.6-3-50.1-10.4l-6.9-3.1-7.5 43.7c12.4 5.4 35.4 10.1 59.3 10.3 55.9 0 92.2-26.1 92.6-66.5.2-22.2-13.9-39.1-44.4-53-18.5-9-29.8-15-29.7-24.1.1-8.1 9.6-16.7 30.3-16.7 17.3-.3 29.8 3.5 39.6 7.4l4.7 2.2 7.9-43z"/>
                                        <path d="M615 153.1h-41.3c-12.8 0-22.4 3.5-28 16.3l-79.5 179.3h56.2l11.2-29.2 68.5.1c1.6 6.8 6.5 29.1 6.5 29.1h49.6L615 153.1zm-65.6 124.9c4.4-11.3 21.2-54.4 21.2-54.4-.3.5 4.4-11.3 7.1-18.6l3.6 16.8s10.1 46.4 12.2 56.2h-44.1z"/>
                                        <path d="M224.1 153.1l-52.3 133.4-5.6-27.1c-9.7-31.2-40-65-73.8-81.9l47.8 170.9h56.6l84.2-195.3h-56.9z"/>
                                        <path d="M121.7 153.1H32.1L31.3 157c70.3 17 116.8 58.1 136.1 107.4L147.5 170c-3.4-12.8-12.7-16.5-24.2-16.6-1.7-.1-1.6-.3-1.6-.3z"/>
                                    </svg>
                                    <svg v-else-if="kaartType === 'mastercard'" class="h-6 w-10" viewBox="0 0 152 108" fill="none">
                                        <circle cx="52" cy="54" r="42" fill="#EB001B"/>
                                        <circle cx="100" cy="54" r="42" fill="#F79E1B"/>
                                        <path d="M76 22.7a42 42 0 010 62.6A42 42 0 0176 22.7z" fill="#FF5F00"/>
                                    </svg>
                                    <svg v-else class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Vervaldatum <span class="text-red-500">*</span>
                                </label>
                                <input
                                    :value="vervalGeformat"
                                    @input="formatVerval"
                                    type="text"
                                    required
                                    placeholder="MM/YY"
                                    autocomplete="cc-exp"
                                    maxlength="5"
                                    inputmode="numeric"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-mono focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    CVV <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="formulier.kaart_cvv"
                                    type="password"
                                    required
                                    placeholder="•••"
                                    autocomplete="cc-csc"
                                    maxlength="4"
                                    inputmode="numeric"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-mono focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                />
                            </div>
                        </div>

                        <div class="pt-2">
                            <button
                                type="submit"
                                :disabled="formulier.processing || !isGeldig"
                                class="w-full rounded-lg py-4 font-bold text-white text-lg transition-all disabled:opacity-50"
                                :class="isGeldig ? 'bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-200' : 'bg-gray-400 cursor-not-allowed'"
                            >
                                <span v-if="formulier.processing" class="flex items-center justify-center gap-3">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.4 0 0 5.4 0 12h4z"/>
                                    </svg>
                                    Betaling verwerken...
                                </span>
                                <span v-else class="flex items-center justify-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Testbetaling bevestigen — €{{ totaal }}
                                </span>
                            </button>

                            <p class="mt-3 text-center text-xs text-amber-600 flex items-center justify-center gap-1 font-medium">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Simulatie — er wordt geen echt geld afgeschreven
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </SchipholLayout>
</template>
