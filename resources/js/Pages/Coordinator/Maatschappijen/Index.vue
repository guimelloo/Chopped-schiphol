<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import CoordinatorLayout from '@/Layouts/CoordinatorLayout.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref } from 'vue'

const { t } = useI18n()

const props = defineProps({
    maatschappijen: { type: Array, default: () => [] },
})

const toonForm = ref(false)
const bewerkId = ref(null)
const verwijderModal = ref(null)

const formulier = useForm({
    naam: '',
    iata_code: '',
    land: '',
    logo_url: '',
    logo_bestand: null,
})

function nieuw() {
    bewerkId.value = null
    formulier.reset()
    toonForm.value = true
}

function bewerk(m) {
    bewerkId.value = m.id
    formulier.naam = m.naam
    formulier.iata_code = m.iata_code
    formulier.land = m.land
    formulier.logo_url = m.logo_url || ''
    toonForm.value = true
}

function opslaan() {
    if (bewerkId.value) {
        formulier.put(`/coordinator/maatschappijen/${bewerkId.value}`, { onSuccess: sluiten })
    } else {
        formulier.post('/coordinator/maatschappijen', { onSuccess: sluiten })
    }
}

function sluiten() {
    toonForm.value = false
    bewerkId.value = null
    formulier.reset()
}

function verwijder() {
    if (!verwijderModal.value) return
    router.delete(`/coordinator/maatschappijen/${verwijderModal.value.id}`, {
        onSuccess: () => { verwijderModal.value = null },
    })
}

function logoGekozen(e) {
    formulier.logo_bestand = e.target.files[0] || null
}
</script>

<template>
    <Head :title="t('coordinator.airlines')" />
    <CoordinatorLayout>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ t('coordinator.airlines') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ maatschappijen.length }} {{ t('nav.airlines').toLowerCase() }}</p>
            </div>
            <button
                @click="nieuw"
                class="flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ t('coordinator.addAirline') }}
            </button>
        </div>

        <!-- Form modal -->
        <div v-if="toonForm" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
                <h2 class="mb-5 text-lg font-bold text-gray-900">
                    {{ bewerkId ? t('common.edit') : t('coordinator.addAirline') }}
                </h2>
                <form @submit.prevent="opslaan" class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Naam <span class="text-red-500">*</span></label>
                            <input v-model="formulier.naam" type="text" required placeholder="KLM Royal Dutch Airlines" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">IATA code <span class="text-red-500">*</span></label>
                            <input v-model="formulier.iata_code" type="text" required maxlength="3" placeholder="KL" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 uppercase" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Land</label>
                            <input v-model="formulier.land" type="text" placeholder="Nederland" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Logo URL</label>
                            <input v-model="formulier.logo_url" type="url" placeholder="https://..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Logo uploaden</label>
                            <input @change="logoGekozen" type="file" accept="image/*" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none" />
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG of SVG. Max. 2 MB.</p>
                        </div>
                    </div>

                    <div v-if="formulier.logo_url" class="flex items-center gap-3 rounded-lg bg-gray-50 p-3">
                        <img :src="formulier.logo_url" alt="logo preview" class="h-12 w-12 rounded object-contain border border-gray-200" />
                        <p class="text-xs text-gray-600">Logo voorbeeld</p>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="sluiten" class="flex-1 rounded-lg border border-gray-300 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">{{ t('common.cancel') }}</button>
                        <button type="submit" :disabled="formulier.processing" class="flex-1 rounded-lg bg-blue-900 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                            {{ formulier.processing ? t('common.loading') : t('common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Airlines grid -->
        <div v-if="!maatschappijen.length" class="rounded-xl border border-gray-200 bg-white p-12 text-center text-gray-500">
            <p>{{ t('common.noResults') }}</p>
        </div>

        <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="m in maatschappijen" :key="m.id" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4 mb-4">
                    <div class="h-14 w-14 shrink-0 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                        <img v-if="m.logo_url" :src="m.logo_url" :alt="m.naam" class="h-full w-full object-contain p-1" />
                        <span v-else class="text-lg font-bold text-blue-900">{{ m.iata_code }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ m.naam }}</p>
                        <p class="text-sm text-gray-500">{{ m.land }}</p>
                        <span class="inline-block mt-1 rounded-full bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-800">{{ m.iata_code }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button @click="bewerk(m)" class="flex-1 rounded-lg bg-blue-50 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-100 transition-colors">
                        {{ t('common.edit') }}
                    </button>
                    <button @click="verwijderModal = m" class="flex-1 rounded-lg bg-red-50 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 transition-colors">
                        {{ t('common.delete') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete modal -->
        <div v-if="verwijderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-gray-900 mb-2 text-center">{{ t('common.delete') }} maatschappij</h3>
                <p class="text-center text-sm text-gray-600 mb-6">{{ verwijderModal.naam }} ({{ verwijderModal.iata_code }})</p>
                <div class="flex gap-3">
                    <button @click="verwijderModal = null" class="flex-1 rounded-lg border border-gray-300 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">{{ t('common.cancel') }}</button>
                    <button @click="verwijder" class="flex-1 rounded-lg bg-red-600 py-2.5 text-sm font-semibold text-white hover:bg-red-700">{{ t('common.delete') }}</button>
                </div>
            </div>
        </div>
    </CoordinatorLayout>
</template>
