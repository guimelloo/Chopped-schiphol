<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import CoordinatorLayout from '@/Layouts/CoordinatorLayout.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref } from 'vue'

const { t } = useI18n()

const props = defineProps({
    vluchten: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
})

const zoek = ref(props.filters?.zoek || '')
const verwijderModal = ref(null)

function zoeken() {
    router.get('/coordinator/vluchten', { zoek: zoek.value }, { preserveScroll: true, replace: true })
}

function bevestigVerwijder(vlucht) {
    verwijderModal.value = vlucht
}

function verwijder() {
    if (!verwijderModal.value) return
    router.delete(`/coordinator/vluchten/${verwijderModal.value.id}`, {
        onSuccess: () => { verwijderModal.value = null },
    })
}

function statusKleur(status) {
    return {
        gepland: 'bg-green-100 text-green-800',
        vertrokken: 'bg-blue-100 text-blue-800',
        geland: 'bg-gray-100 text-gray-800',
        geannuleerd: 'bg-red-100 text-red-800',
    }[status] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
    <Head :title="t('coordinator.manageFlights')" />
    <CoordinatorLayout>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ t('coordinator.manageFlights') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ vluchten.length }} {{ t('nav.flights').toLowerCase() }}</p>
            </div>
            <Link href="/coordinator/vluchten/toevoegen" class="flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ t('coordinator.addFlight') }}
            </Link>
        </div>

        <!-- Search -->
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form @submit.prevent="zoeken" class="flex gap-3">
                <input
                    v-model="zoek"
                    type="text"
                    :placeholder="`${t('common.search')}...`"
                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                />
                <button type="submit" class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 transition-colors">
                    {{ t('common.search') }}
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div v-if="!vluchten.length" class="p-12 text-center text-gray-500">
                <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <p class="font-medium">{{ t('common.noResults') }}</p>
            </div>
            <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('flight.number') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('flight.airline') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('flight.route') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('flight.departure') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('flight.gate') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('flight.status') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="vlucht in vluchten" :key="vlucht.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-bold text-blue-900">{{ vlucht.vlucht_nummer }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ vlucht.luchtvaartmaatschappij?.naam }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ vlucht.vertrek_luchthaven?.split('(')[0]?.trim() }}
                                <span class="text-gray-400 mx-1">→</span>
                                {{ vlucht.aankomst_luchthaven?.split('(')[0]?.trim() }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ vlucht.vertrek_tijd?.slice(0, 16) }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-700">{{ vlucht.gate?.nummer ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold', statusKleur(vlucht.status)]">
                                    {{ t('flight.statuses.' + vlucht.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <Link :href="`/coordinator/vluchten/${vlucht.id}/wijzigen`" class="rounded bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800 hover:bg-blue-200 transition-colors">
                                        {{ t('common.edit') }}
                                    </Link>
                                    <button @click="bevestigVerwijder(vlucht)" class="rounded bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 hover:bg-red-200 transition-colors">
                                        {{ t('common.delete') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete modal -->
        <div v-if="verwijderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mx-auto">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-center text-lg font-bold text-gray-900 mb-2">{{ t('common.delete') }} {{ t('nav.flights').toLowerCase() }}</h3>
                <p class="text-center text-sm text-gray-600 mb-6">
                    {{ verwijderModal.vlucht_nummer }} — {{ verwijderModal.vertrek_luchthaven?.split('(')[0] }} → {{ verwijderModal.aankomst_luchthaven?.split('(')[0] }}
                </p>
                <div class="flex gap-3">
                    <button @click="verwijderModal = null" class="flex-1 rounded-lg border border-gray-300 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        {{ t('common.cancel') }}
                    </button>
                    <button @click="verwijder" class="flex-1 rounded-lg bg-red-600 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                        {{ t('common.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </CoordinatorLayout>
</template>
