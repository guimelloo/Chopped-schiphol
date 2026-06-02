<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-blue-900 text-white shadow-lg">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-8">
                        <Link :href="route('vluchten.index')" class="flex items-center gap-2 font-bold text-xl tracking-wide">
                            <svg class="h-7 w-7 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                            Schiphol Vluchten
                        </Link>
                        <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                            <Link :href="route('vluchten.index')" class="hover:text-yellow-300 transition-colors">
                                Vluchten
                            </Link>
                            <Link :href="route('vluchten.zoek')" class="hover:text-yellow-300 transition-colors">
                                Zoeken
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <Link :href="route('coordinator.login')" class="rounded bg-yellow-400 px-3 py-1.5 font-semibold text-blue-900 hover:bg-yellow-300 transition-colors">
                            Coördinator
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>

        <footer class="mt-16 bg-blue-900 text-white/70 text-center py-6 text-sm">
            &copy; {{ new Date().getFullYear() }} Schiphol Vluchtbeheer — Amsterdam Airport Schiphol
        </footer>
    </div>
</template>
