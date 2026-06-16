<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import CoordinatorLayout from '@/Layouts/CoordinatorLayout.vue'
import { useI18n } from '@/composables/useI18n.js'

const { t } = useI18n()

const props = defineProps({
    maatschappijen: { type: Array, default: () => [] },
    gates: { type: Array, default: () => [] },
})

const formulier = useForm({
    luchtvaartmaatschappij_id: '',
    vlucht_nummer: '',
    vertrek_luchthaven: '',
    aankomst_luchthaven: '',
    vertrek_tijd: '',
    aankomst_tijd: '',
    vliegtuig_type: '',
    prijs_economy: '',
    prijs_business: '',
    beschikbaar_economy: '',
    beschikbaar_business: '',
    gate_id: '',
    status: 'gepland',
    services: '',
})

function opslaan() {
    formulier.post('/coordinator/vluchten')
}
</script>

<template>
    <Head :title="t('coordinator.addFlight')" />
    <CoordinatorLayout>
        <div class="mb-6 flex items-center gap-4">
            <Link href="/coordinator/vluchten" class="flex items-center gap-2 text-sm text-blue-700 hover:text-blue-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ t('common.back') }}
            </Link>
            <h1 class="text-2xl font-bold text-gray-900">{{ t('coordinator.addFlight') }}</h1>
        </div>

        <form @submit.prevent="opslaan" class="space-y-6">
            <!-- Basic info -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-gray-900">{{ t('flight.details') }}</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.airline') }} <span class="text-red-500">*</span></label>
                        <select v-model="formulier.luchtvaartmaatschappij_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">— {{ t('flight.airline') }} —</option>
                            <option v-for="m in maatschappijen" :key="m.id" :value="m.id">{{ m.naam }} ({{ m.iata_code }})</option>
                        </select>
                        <p v-if="formulier.errors.luchtvaartmaatschappij_id" class="mt-1 text-xs text-red-600">{{ formulier.errors.luchtvaartmaatschappij_id }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.number') }} <span class="text-red-500">*</span></label>
                        <input v-model="formulier.vlucht_nummer" type="text" required placeholder="KL1234" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                        <p v-if="formulier.errors.vlucht_nummer" class="mt-1 text-xs text-red-600">{{ formulier.errors.vlucht_nummer }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.departure') }} <span class="text-red-500">*</span></label>
                        <input v-model="formulier.vertrek_luchthaven" type="text" required placeholder="Amsterdam Schiphol (AMS)" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.arrival') }} <span class="text-red-500">*</span></label>
                        <input v-model="formulier.aankomst_luchthaven" type="text" required placeholder="London Heathrow (LHR)" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.departure') }} (datum/tijd) <span class="text-red-500">*</span></label>
                        <input v-model="formulier.vertrek_tijd" type="datetime-local" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.arrival') }} (datum/tijd) <span class="text-red-500">*</span></label>
                        <input v-model="formulier.aankomst_tijd" type="datetime-local" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.aircraft') }} <span class="text-red-500">*</span></label>
                        <input v-model="formulier.vliegtuig_type" type="text" required placeholder="Boeing 737-800" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.gate') }}</label>
                        <select v-model="formulier.gate_id" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">— {{ t('flight.gate') }} —</option>
                            <option v-for="g in gates" :key="g.id" :value="g.id">{{ t('flight.terminal') }} {{ g.terminal }} — {{ t('flight.gate') }} {{ g.nummer }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.status') }}</label>
                        <select v-model="formulier.status" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="gepland">{{ t('flight.statuses.gepland') }}</option>
                            <option value="vertrokken">{{ t('flight.statuses.vertrokken') }}</option>
                            <option value="geland">{{ t('flight.statuses.geland') }}</option>
                            <option value="geannuleerd">{{ t('flight.statuses.geannuleerd') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pricing & seats -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-gray-900">{{ t('common.price') }} &amp; {{ t('flight.seats') }}</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.economy') }} — {{ t('common.price') }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                            <input v-model="formulier.prijs_economy" type="number" min="0" step="0.01" placeholder="0.00" class="w-full rounded-lg border border-gray-300 pl-7 pr-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.economy') }} — {{ t('flight.seats') }}</label>
                        <input v-model="formulier.beschikbaar_economy" type="number" min="0" placeholder="150" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.business') }} — {{ t('common.price') }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                            <input v-model="formulier.prijs_business" type="number" min="0" step="0.01" placeholder="0.00" class="w-full rounded-lg border border-gray-300 pl-7 pr-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.business') }} — {{ t('flight.seats') }}</label>
                        <input v-model="formulier.beschikbaar_business" type="number" min="0" placeholder="20" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-gray-900">{{ t('flight.services') }}</h2>
                <textarea v-model="formulier.services" rows="3" placeholder="Maaltijd, WiFi, Entertainment..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"></textarea>
                <p class="mt-1 text-xs text-gray-500">Komma-gescheiden</p>
            </div>

            <div class="flex justify-end gap-3">
                <Link href="/coordinator/vluchten" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    {{ t('common.cancel') }}
                </Link>
                <button type="submit" :disabled="formulier.processing" class="rounded-lg bg-blue-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition-colors disabled:opacity-50">
                    {{ formulier.processing ? t('common.loading') : t('common.save') }}
                </button>
            </div>
        </form>
    </CoordinatorLayout>
</template>
