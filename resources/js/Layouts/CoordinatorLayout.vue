<script setup>
import { Link, router } from '@inertiajs/vue3';

function uitloggen() {
    router.post(route('coordinator.logout'));
}
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-blue-900 text-white shadow-lg">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-6">
                        <Link :href="route('coordinator.dashboard')" class="flex items-center gap-2 font-bold text-lg">
                            <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                            Coördinator Dashboard
                        </Link>
                        <span class="hidden md:block h-5 w-px bg-white/30"></span>
                        <Link :href="route('coordinator.dashboard')" class="hidden md:block text-sm hover:text-yellow-300 transition-colors">
                            Overzicht
                        </Link>
                    </div>
                    <button
                        @click="uitloggen"
                        class="rounded bg-red-600 px-3 py-1.5 text-sm font-semibold hover:bg-red-500 transition-colors"
                    >
                        Uitloggen
                    </button>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
