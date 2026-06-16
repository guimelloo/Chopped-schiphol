<script setup>
import { Head, Link } from '@inertiajs/vue3'
import DirecteurLayout from '@/Layouts/DirecteurLayout.vue'
import { useI18n } from '@/composables/useI18n.js'

const { t } = useI18n()

const props = defineProps({
    directeur: Object,
    stats: {
        type: Object,
        default: () => ({
            totale_omzet: 0,
            totaal_boekingen: 0,
            actieve_coordinatoren: 0,
            vluchten_vandaag: 0,
        }),
    },
    recente_boekingen: { type: Array, default: () => [] },
    coordinatoren: { type: Array, default: () => [] },
})
</script>

<template>
    <Head :title="t('director.dashboard')" />
    <DirecteurLayout>
        <!-- Welcome banner -->
        <div class="mb-8 rounded-xl bg-gray-900 p-6 text-white shadow">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">{{ t('director.dashboard') }}</h1>
                    <p class="mt-1 text-gray-300">Amsterdam Airport Schiphol — {{ directeur?.naam ?? 'Directeur' }}</p>
                </div>
                <div class="text-sm text-gray-400">
                    {{ new Date().toLocaleDateString('nl-NL', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>
        </div>

        <!-- Stats cards -->
        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">{{ t('director.totalRevenue') }}</p>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-gray-900">€{{ Number(stats.totale_omzet).toLocaleString('nl-NL') }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">{{ t('director.totalBookings') }}</p>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-gray-900">{{ stats.totaal_boekingen }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">{{ t('director.activeCoordinators') }}</p>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-yellow-100">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-gray-900">{{ stats.actieve_coordinatoren }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">Vluchten vandaag</p>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-purple-100">
                        <svg class="h-5 w-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-gray-900">{{ stats.vluchten_vandaag }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Quick actions -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-bold text-gray-900">Snelle toegang</h2>
                <div class="space-y-2">
                    <Link href="/directeur/coordinatoren" class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100">
                                <svg class="h-4 w-4 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ t('director.coordinators') }}</span>
                        </div>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                    <Link href="/directeur/boekingen" class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                                <svg class="h-4 w-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ t('director.allBookings') }}</span>
                        </div>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                    <Link href="/directeur/coordinatoren/toevoegen" class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                <svg class="h-4 w-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ t('director.addCoordinator') }}</span>
                        </div>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Recent bookings -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Recente boekingen</h2>
                    <Link href="/directeur/boekingen" class="text-sm text-blue-700 hover:text-blue-900 font-medium">Alle →</Link>
                </div>
                <div v-if="!recente_boekingen.length" class="text-center py-8 text-gray-400 text-sm">
                    Nog geen boekingen
                </div>
                <div v-else class="space-y-2">
                    <div v-for="b in recente_boekingen.slice(0, 5)" :key="b.id" class="flex items-center justify-between rounded-lg border border-gray-100 px-3 py-2.5">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ b.boekings_nummer }}</p>
                            <p class="text-xs text-gray-500">{{ b.naam_reiziger }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-blue-900">€{{ b.prijs }}</p>
                            <p class="text-xs text-gray-400">{{ b.aangemaakt_op }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DirecteurLayout>
</template>
