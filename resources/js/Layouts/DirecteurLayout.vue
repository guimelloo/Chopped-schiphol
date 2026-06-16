<script setup>
import { Link, router } from '@inertiajs/vue3'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref } from 'vue'

const { t } = useI18n()
const mobileOpen = ref(false)

function uitloggen() {
    router.post('/directeur/uitloggen')
}
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-gray-900 text-white shadow-lg">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-4">
                        <Link href="/directeur/dashboard" class="flex items-center gap-2 font-bold text-lg shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                            <span class="hidden sm:inline">{{ t('director.dashboard') }}</span>
                        </Link>
                        <div class="hidden lg:flex items-center gap-1 ml-4">
                            <Link href="/directeur/dashboard" class="rounded px-3 py-1.5 text-sm font-medium hover:bg-white/10 transition-colors">
                                {{ t('nav.dashboard') }}
                            </Link>
                            <Link href="/directeur/coordinatoren" class="rounded px-3 py-1.5 text-sm font-medium hover:bg-white/10 transition-colors">
                                {{ t('nav.coordinators') }}
                            </Link>
                            <Link href="/directeur/boekingen" class="rounded px-3 py-1.5 text-sm font-medium hover:bg-white/10 transition-colors">
                                {{ t('nav.bookings') }}
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <LanguageSwitcher />
                        <button @click="uitloggen" class="hidden sm:block rounded bg-red-600 px-3 py-1.5 text-sm font-semibold hover:bg-red-500 transition-colors">
                            {{ t('nav.logout') }}
                        </button>
                        <button @click="mobileOpen = !mobileOpen" class="lg:hidden rounded p-1.5 hover:bg-white/10 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="mobileOpen" class="lg:hidden border-t border-gray-800 bg-gray-900 px-4 py-3 space-y-1">
                <Link href="/directeur/dashboard" class="block py-2 text-sm font-medium hover:text-yellow-300" @click="mobileOpen = false">{{ t('nav.dashboard') }}</Link>
                <Link href="/directeur/coordinatoren" class="block py-2 text-sm font-medium hover:text-yellow-300" @click="mobileOpen = false">{{ t('nav.coordinators') }}</Link>
                <Link href="/directeur/boekingen" class="block py-2 text-sm font-medium hover:text-yellow-300" @click="mobileOpen = false">{{ t('nav.bookings') }}</Link>
                <button @click="uitloggen" class="block w-full mt-2 rounded bg-red-600 px-3 py-2 text-sm font-semibold text-left">{{ t('nav.logout') }}</button>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
