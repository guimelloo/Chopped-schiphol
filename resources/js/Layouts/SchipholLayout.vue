<script setup>
import { Link } from '@inertiajs/vue3'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref } from 'vue'

const { t } = useI18n()
const mobileOpen = ref(false)
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-blue-900 text-white shadow-lg">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo & nav links -->
                    <div class="flex items-center gap-8">
                        <Link :href="route('vluchten.index')" class="flex items-center gap-2 font-bold text-xl tracking-wide shrink-0">
                            <svg class="h-7 w-7 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                            <span class="hidden sm:inline">Chopped Schiphol</span>
                        </Link>
                        <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                            <Link :href="route('vluchten.index')" class="hover:text-yellow-300 transition-colors">
                                {{ t('nav.flights') }}
                            </Link>
                            <Link :href="route('vluchten.zoek')" class="hover:text-yellow-300 transition-colors">
                                {{ t('nav.search') }}
                            </Link>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center gap-3">
                        <LanguageSwitcher />
                        <div class="hidden md:flex items-center gap-2">
                            <Link :href="route('coordinator.login')" class="rounded bg-yellow-400 px-3 py-1.5 text-sm font-semibold text-blue-900 hover:bg-yellow-300 transition-colors">
                                {{ t('nav.coordinator') }}
                            </Link>
                            <Link href="/directeur/inloggen" class="rounded border border-white/30 px-3 py-1.5 text-sm font-semibold text-white hover:bg-white/10 transition-colors">
                                {{ t('nav.director') }}
                            </Link>
                        </div>
                        <!-- Mobile menu button -->
                        <button @click="mobileOpen = !mobileOpen" class="md:hidden rounded p-1.5 hover:bg-white/10 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="mobileOpen" class="md:hidden border-t border-blue-800 bg-blue-900 px-4 py-3 space-y-2">
                <Link :href="route('vluchten.index')" class="block py-2 text-sm font-medium hover:text-yellow-300 transition-colors" @click="mobileOpen = false">
                    {{ t('nav.flights') }}
                </Link>
                <Link :href="route('vluchten.zoek')" class="block py-2 text-sm font-medium hover:text-yellow-300 transition-colors" @click="mobileOpen = false">
                    {{ t('nav.search') }}
                </Link>
                <div class="pt-2 border-t border-blue-800 flex flex-col gap-2">
                    <Link :href="route('coordinator.login')" class="inline-flex justify-center rounded bg-yellow-400 px-3 py-2 text-sm font-semibold text-blue-900">
                        {{ t('nav.coordinator') }}
                    </Link>
                    <Link href="/directeur/inloggen" class="inline-flex justify-center rounded border border-white/30 px-3 py-2 text-sm font-semibold text-white">
                        {{ t('nav.director') }}
                    </Link>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>

        <footer class="mt-16 bg-blue-900 text-white/70 text-center py-6 text-sm">
            &copy; {{ new Date().getFullYear() }} Chopped Schiphol — Amsterdam Airport
        </footer>
    </div>
</template>
