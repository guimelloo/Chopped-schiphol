<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const formulier = useForm({
    gebruikersnaam: '',
    wachtwoord: '',
    onthouden: false,
});

function inloggen() {
    formulier.post(route('coordinator.login.post'), {
        onFinish: () => formulier.reset('wachtwoord'),
    });
}
</script>

<template>
    <Head title="Coördinator Inloggen" />

    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-900 to-blue-700 px-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="mb-8 text-center text-white">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-yellow-400 shadow-lg">
                    <svg class="h-9 w-9 text-blue-900" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Schiphol Vluchtbeheer</h1>
                <p class="mt-1 text-blue-200">Coördinator portaal</p>
            </div>

            <!-- Inlogformulier -->
            <div class="rounded-2xl bg-white p-8 shadow-2xl">
                <h2 class="mb-6 text-xl font-bold text-gray-900">Inloggen</h2>

                <!-- Foutmelding -->
                <div
                    v-if="formulier.errors.gebruikersnaam"
                    class="mb-5 flex items-center gap-3 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700"
                >
                    <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ formulier.errors.gebruikersnaam }}
                </div>

                <form @submit.prevent="inloggen" class="space-y-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Gebruikersnaam
                        </label>
                        <input
                            v-model="formulier.gebruikersnaam"
                            type="text"
                            required
                            autocomplete="username"
                            placeholder="jan.devries"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            :class="{ 'border-red-400 bg-red-50': formulier.errors.gebruikersnaam }"
                        />
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Wachtwoord
                        </label>
                        <input
                            v-model="formulier.wachtwoord"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        />
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            v-model="formulier.onthouden"
                            id="onthouden"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="onthouden" class="text-sm text-gray-600">Onthoud mij</label>
                    </div>

                    <button
                        type="submit"
                        :disabled="formulier.processing"
                        class="w-full rounded-lg bg-blue-900 py-3 font-bold text-white transition hover:bg-blue-800 disabled:opacity-60"
                    >
                        {{ formulier.processing ? 'Bezig met inloggen...' : 'Inloggen' }}
                    </button>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-5 text-center text-xs text-gray-400">
                    Testaccounts: <strong>admin</strong> / admin123
                </div>
            </div>
        </div>
    </div>
</template>
