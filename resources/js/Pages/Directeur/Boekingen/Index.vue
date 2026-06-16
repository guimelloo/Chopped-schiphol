<script setup>
import { Head, router } from '@inertiajs/vue3'
import DirecteurLayout from '@/Layouts/DirecteurLayout.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref, reactive } from 'vue'

const { t } = useI18n()

const props = defineProps({
    boekingen: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
    stats: {
        type: Object,
        default: () => ({ totale_omzet: 0, totaal: 0, bevestigd: 0, geannuleerd: 0 }),
    },
})

const filters = reactive({
    zoek: props.filters?.zoek || '',
    status: props.filters?.status || '',
    van: props.filters?.van || '',
    tot: props.filters?.tot || '',
})

function filteren() {
    router.get('/directeur/boekingen', filters, { preserveScroll: true, replace: true })
}

function resetFilters() {
    filters.zoek = ''
    filters.status = ''
    filters.van = ''
    filters.tot = ''
    filteren()
}

const statusKleur = {
    bevestigd: 'bg-green-100 text-green-800',
    in_afwachting: 'bg-yellow-100 text-yellow-800',
    geannuleerd: 'bg-red-100 text-red-800',
}
</script>

<template>
    <Head :title="t('director.allBookings')" />
    <DirecteurLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ t('director.allBookings') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ boekingen.length }} {{ t('nav.bookings').toLowerCase() }}</p>
        </div>

        <!-- Stats bar -->
        <div class="mb-6 grid gap-3 sm:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm text-center">
                <p class="text-xs text-gray-500">{{ t('director.totalRevenue') }}</p>
                <p class="text-xl font-extrabold text-gray-900">€{{ Number(stats.totale_omzet).toLocaleString('nl-NL') }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm text-center">
                <p class="text-xs text-gray-500">{{ t('director.totalBookings') }}</p>
                <p class="text-xl font-extrabold text-gray-900">{{ stats.totaal }}</p>
            </div>
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 shadow-sm text-center">
                <p class="text-xs text-green-600">Bevestigd</p>
                <p class="text-xl font-extrabold text-green-800">{{ stats.bevestigd }}</p>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 shadow-sm text-center">
                <p class="text-xs text-red-600">Geannuleerd</p>
                <p class="text-xl font-extrabold text-red-800">{{ stats.geannuleerd }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form @submit.prevent="filteren" class="flex flex-wrap gap-3">
                <input
                    v-model="filters.zoek"
                    type="text"
                    placeholder="Naam, e-mail of boekingsnr..."
                    class="flex-1 min-w-[200px] rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                />
                <select v-model="filters.status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="">Alle statussen</option>
                    <option value="bevestigd">{{ t('booking.status.bevestigd') }}</option>
                    <option value="in_afwachting">{{ t('booking.status.in_afwachting') }}</option>
                    <option value="geannuleerd">{{ t('booking.status.geannuleerd') }}</option>
                </select>
                <input v-model="filters.van" type="date" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                <input v-model="filters.tot" type="date" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">{{ t('common.search') }}</button>
                <button type="button" @click="resetFilters" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Reset</button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div v-if="!boekingen.length" class="p-12 text-center text-gray-500">
                <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="font-medium">{{ t('common.noResults') }}</p>
            </div>
            <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('booking.number') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('booking.passenger') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('flight.number') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('booking.seatClass') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('common.price') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('common.date') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="b in boekingen" :key="b.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-bold text-blue-900">{{ b.boekings_nummer }}</td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ b.naam_reiziger }}</p>
                                <p class="text-xs text-gray-500">{{ b.email_reiziger }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ b.vlucht?.vlucht_nummer ?? b.vlucht_nummer }}</td>
                            <td class="px-4 py-3">
                                <span :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize', b.stoelklasse === 'business' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800']">
                                    {{ b.stoelklasse }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-gray-900">€{{ b.prijs }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ b.aangemaakt_op }}</td>
                            <td class="px-4 py-3">
                                <span :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold', statusKleur[b.status] || 'bg-gray-100 text-gray-700']">
                                    {{ t('booking.status.' + b.status) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </DirecteurLayout>
</template>
