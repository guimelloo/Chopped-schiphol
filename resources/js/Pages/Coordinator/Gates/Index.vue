<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import CoordinatorLayout from '@/Layouts/CoordinatorLayout.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref } from 'vue'

const { t } = useI18n()

const props = defineProps({
    gates: { type: Array, default: () => [] },
    vluchten: { type: Array, default: () => [] },
})

const toewijsModal = ref(null)
const toewijsForm = useForm({ vlucht_id: '' })

function openToewijzen(gate) {
    toewijsModal.value = gate
    toewijsForm.vlucht_id = ''
}

function toewijzen() {
    if (!toewijsModal.value) return
    toewijsForm.post(`/coordinator/gates/${toewijsModal.value.id}/toewijzen`, {
        onSuccess: () => { toewijsModal.value = null },
    })
}

const nieuwForm = useForm({ terminal: 'A', nummer: '', type: 'standaard' })
const toonNieuwForm = ref(false)

function gateToevoegen() {
    nieuwForm.post('/coordinator/gates', {
        onSuccess: () => { toonNieuwForm.value = false; nieuwForm.reset() },
    })
}
</script>

<template>
    <Head :title="t('coordinator.gates')" />
    <CoordinatorLayout>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ t('coordinator.gates') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ gates.length }} gates</p>
            </div>
            <button
                @click="toonNieuwForm = !toonNieuwForm"
                class="flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition-colors"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Gate toevoegen
            </button>
        </div>

        <!-- Add gate form -->
        <div v-if="toonNieuwForm" class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-6">
            <h2 class="mb-4 font-bold text-gray-900">Gate toevoegen</h2>
            <form @submit.prevent="gateToevoegen" class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('flight.terminal') }}</label>
                    <select v-model="nieuwForm.terminal" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option v-for="t in ['A','B','C','D','E','F','G','H']" :key="t" :value="t">{{ t }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Nummer <span class="text-red-500">*</span></label>
                    <input v-model="nieuwForm.nummer" type="text" required placeholder="B14" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Type</label>
                    <select v-model="nieuwForm.type" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="standaard">Standaard</option>
                        <option value="uitgebreid">Uitgebreid</option>
                    </select>
                </div>
                <div class="sm:col-span-3 flex gap-3 justify-end">
                    <button type="button" @click="toonNieuwForm = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">{{ t('common.cancel') }}</button>
                    <button type="submit" :disabled="nieuwForm.processing" class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">{{ t('common.save') }}</button>
                </div>
            </form>
        </div>

        <!-- Gates by terminal -->
        <div class="space-y-6">
            <div v-for="terminal in ['A','B','C','D','E','F','G','H']" :key="terminal">
                <template v-if="gates.filter(g => g.terminal === terminal).length">
                    <h2 class="mb-3 text-lg font-bold text-gray-800">{{ t('flight.terminal') }} {{ terminal }}</h2>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div
                            v-for="gate in gates.filter(g => g.terminal === terminal)"
                            :key="gate.id"
                            class="rounded-xl border bg-white p-4 shadow-sm"
                            :class="gate.vlucht ? 'border-blue-300 bg-blue-50' : 'border-gray-200'"
                        >
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="text-2xl font-extrabold text-blue-900">{{ gate.terminal }}{{ gate.nummer }}</p>
                                    <span :class="['inline-block mt-1 rounded-full px-2 py-0.5 text-xs font-semibold', gate.type === 'uitgebreid' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700']">
                                        {{ gate.type === 'uitgebreid' ? 'Uitgebreid' : 'Standaard' }}
                                    </span>
                                </div>
                                <div :class="['h-3 w-3 rounded-full mt-1', gate.vlucht ? 'bg-green-500' : 'bg-gray-300']"></div>
                            </div>
                            <div v-if="gate.vlucht" class="mb-3 text-sm">
                                <p class="font-semibold text-gray-900">{{ gate.vlucht.vlucht_nummer }}</p>
                                <p class="text-gray-600 text-xs">{{ gate.vlucht.vertrek_luchthaven?.split('(')[0] }} → {{ gate.vlucht.aankomst_luchthaven?.split('(')[0] }}</p>
                                <p class="text-gray-500 text-xs">{{ gate.vlucht.vertrek_tijd?.slice(11, 16) }}</p>
                            </div>
                            <div v-else class="mb-3 text-sm text-gray-400">Vrij</div>
                            <button
                                @click="openToewijzen(gate)"
                                class="w-full rounded-lg border border-blue-900 py-1.5 text-xs font-semibold text-blue-900 hover:bg-blue-900 hover:text-white transition-colors"
                            >
                                {{ t('coordinator.assignGate') }}
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div v-if="!gates.length" class="rounded-xl border border-gray-200 bg-white p-12 text-center text-gray-500">
                <p>{{ t('common.noResults') }}</p>
            </div>
        </div>

        <!-- Assign modal -->
        <div v-if="toewijsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ t('coordinator.assignGate') }}</h3>
                <p class="text-sm text-gray-500 mb-5">{{ t('flight.terminal') }} {{ toewijsModal.terminal }} — {{ t('flight.gate') }} {{ toewijsModal.nummer }}</p>
                <div class="mb-5">
                    <label class="mb-1 block text-sm font-semibold text-gray-700">{{ t('nav.flights') }}</label>
                    <select v-model="toewijsForm.vlucht_id" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">— Geen vlucht —</option>
                        <option v-for="v in vluchten" :key="v.id" :value="v.id">
                            {{ v.vlucht_nummer }} — {{ v.vertrek_luchthaven?.split('(')[0] }} → {{ v.aankomst_luchthaven?.split('(')[0] }}
                        </option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button @click="toewijsModal = null" class="flex-1 rounded-lg border border-gray-300 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">{{ t('common.cancel') }}</button>
                    <button @click="toewijzen" :disabled="toewijsForm.processing" class="flex-1 rounded-lg bg-blue-900 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">{{ t('common.save') }}</button>
                </div>
            </div>
        </div>
    </CoordinatorLayout>
</template>
