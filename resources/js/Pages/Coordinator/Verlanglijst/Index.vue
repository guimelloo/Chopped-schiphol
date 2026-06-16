<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import CoordinatorLayout from '@/Layouts/CoordinatorLayout.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref } from 'vue'

const { t } = useI18n()

const props = defineProps({
    verlanglijst: { type: Array, default: () => [] },
    maatschappijen: { type: Array, default: () => [] },
})

const toonForm = ref(false)

const formulier = useForm({
    maatschappij_naam: '',
    bestemming: '',
    gewenste_datum: '',
    stoelklasse: 'economy',
    opmerkingen: '',
    prioriteit: 'normaal',
})

function toevoegen() {
    formulier.post('/coordinator/verlanglijst', {
        onSuccess: () => {
            toonForm.value = false
            formulier.reset()
        },
    })
}

function verwijder(id) {
    router.delete(`/coordinator/verlanglijst/${id}`)
}

const prioriteitKleur = {
    hoog: 'bg-red-100 text-red-800',
    normaal: 'bg-yellow-100 text-yellow-800',
    laag: 'bg-gray-100 text-gray-700',
}
</script>

<template>
    <Head :title="t('coordinator.wishlist')" />
    <CoordinatorLayout>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ t('coordinator.wishlist') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ verlanglijst.length }} items</p>
            </div>
            <button
                @click="toonForm = !toonForm"
                class="flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ t('coordinator.addWish') }}
            </button>
        </div>

        <!-- Add form -->
        <div v-if="toonForm" class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-6 shadow-sm">
            <h2 class="mb-5 font-bold text-gray-900">{{ t('coordinator.addWish') }}</h2>
            <form @submit.prevent="toevoegen" class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.airline') }}</label>
                        <input v-model="formulier.maatschappij_naam" type="text" :placeholder="t('flight.airline')" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Bestemming <span class="text-red-500">*</span></label>
                        <input v-model="formulier.bestemming" type="text" required placeholder="New York (JFK)" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Gewenste datum</label>
                        <input v-model="formulier.gewenste_datum" type="date" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('booking.seatClass') }}</label>
                        <select v-model="formulier.stoelklasse" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="economy">{{ t('flight.economy') }}</option>
                            <option value="business">{{ t('flight.business') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Prioriteit</label>
                        <select v-model="formulier.prioriteit" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="hoog">Hoog</option>
                            <option value="normaal">Normaal</option>
                            <option value="laag">Laag</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Opmerkingen</label>
                        <textarea v-model="formulier.opmerkingen" rows="2" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="toonForm = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">{{ t('common.cancel') }}</button>
                    <button type="submit" :disabled="formulier.processing" class="rounded-lg bg-blue-900 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-800">{{ t('common.save') }}</button>
                </div>
            </form>
        </div>

        <!-- List -->
        <div v-if="!verlanglijst.length" class="rounded-xl border border-gray-200 bg-white p-12 text-center text-gray-500">
            <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <p class="font-medium">Verlanglijst is leeg</p>
            <p class="text-sm mt-1">Voeg gewenste vluchten en maatschappijen toe.</p>
        </div>

        <div v-else class="space-y-3">
            <div
                v-for="item in verlanglijst"
                :key="item.id"
                class="flex items-start justify-between gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition-shadow"
            >
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-5 w-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-bold text-gray-900">{{ item.bestemming }}</p>
                            <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold', prioriteitKleur[item.prioriteit] || 'bg-gray-100 text-gray-700']">
                                {{ item.prioriteit }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <span v-if="item.maatschappij_naam">{{ t('flight.airline') }}: <strong>{{ item.maatschappij_naam }}</strong></span>
                            <span v-if="item.gewenste_datum">Datum: <strong>{{ item.gewenste_datum }}</strong></span>
                            <span>Klasse: <strong class="capitalize">{{ item.stoelklasse }}</strong></span>
                        </div>
                        <p v-if="item.opmerkingen" class="mt-1 text-xs text-gray-500">{{ item.opmerkingen }}</p>
                    </div>
                </div>
                <button
                    @click="verwijder(item.id)"
                    class="shrink-0 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100 transition-colors"
                >
                    {{ t('common.delete') }}
                </button>
            </div>
        </div>
    </CoordinatorLayout>
</template>
