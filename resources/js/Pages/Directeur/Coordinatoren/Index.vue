<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import DirecteurLayout from '@/Layouts/DirecteurLayout.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref } from 'vue'

const { t } = useI18n()

const props = defineProps({
    coordinatoren: { type: Array, default: () => [] },
})

const verwijderModal = ref(null)

function verwijder() {
    if (!verwijderModal.value) return
    router.delete(`/directeur/coordinatoren/${verwijderModal.value.id}`, {
        onSuccess: () => { verwijderModal.value = null },
    })
}
</script>

<template>
    <Head :title="t('director.coordinators')" />
    <DirecteurLayout>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ t('director.coordinators') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ coordinatoren.length }} {{ t('nav.coordinators').toLowerCase() }}</p>
            </div>
            <Link href="/directeur/coordinatoren/toevoegen" class="flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ t('director.addCoordinator') }}
            </Link>
        </div>

        <div v-if="!coordinatoren.length" class="rounded-xl border border-gray-200 bg-white p-12 text-center text-gray-500">
            <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="font-medium">{{ t('common.noResults') }}</p>
        </div>

        <div v-else class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('common.name') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Gebruikersnaam</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('common.email') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('common.status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">{{ t('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="(coordinator, idx) in coordinatoren" :key="coordinator.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ idx + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-xs font-bold text-yellow-800">
                                    {{ coordinator.naam?.slice(0, 2).toUpperCase() }}
                                </div>
                                <span class="font-semibold text-gray-900">{{ coordinator.naam }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ coordinator.gebruikersnaam }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ coordinator.email }}</td>
                        <td class="px-4 py-3">
                            <span :class="['rounded-full px-2.5 py-0.5 text-xs font-semibold', coordinator.actief ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600']">
                                {{ coordinator.actief ? 'Actief' : 'Inactief' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Link :href="`/directeur/coordinatoren/${coordinator.id}/wijzigen`" class="rounded bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800 hover:bg-blue-200 transition-colors">
                                    {{ t('common.edit') }}
                                </Link>
                                <button @click="verwijderModal = coordinator" class="rounded bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700 hover:bg-red-200 transition-colors">
                                    {{ t('common.delete') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Delete modal -->
        <div v-if="verwijderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mx-auto">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-center text-lg font-bold text-gray-900 mb-2">{{ t('common.delete') }} coördinator</h3>
                <p class="text-center text-sm text-gray-600 mb-6">{{ verwijderModal.naam }} ({{ verwijderModal.gebruikersnaam }})</p>
                <div class="flex gap-3">
                    <button @click="verwijderModal = null" class="flex-1 rounded-lg border border-gray-300 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">{{ t('common.cancel') }}</button>
                    <button @click="verwijder" class="flex-1 rounded-lg bg-red-600 py-2.5 text-sm font-semibold text-white hover:bg-red-700">{{ t('common.delete') }}</button>
                </div>
            </div>
        </div>
    </DirecteurLayout>
</template>
