<script setup>
import { Head, Link } from '@inertiajs/vue3'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import { useI18n } from '@/composables/useI18n.js'

const { t } = useI18n()
</script>

<template>
    <Head title="Dashboard — Chopped Schiphol" />

    <div class="min-h-screen bg-slate-950 text-white">
        <div class="border-b border-white/5 px-4 py-3 flex items-center justify-between">
            <Link href="/" class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-400">
                    <svg class="h-4 w-4 text-slate-950" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-[10px] font-bold tracking-[0.2em] text-yellow-400 uppercase">Chopped</span>
                    <span class="text-xs font-extrabold text-white">Schiphol</span>
                </div>
            </Link>
            <LanguageSwitcher />
        </div>

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-white">{{ t('nav.dashboard') }}</h1>
                <p class="mt-1 text-slate-400 text-sm">U bent ingelogd bij Chopped Schiphol</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Link :href="route('vluchten.index')" class="group rounded-2xl border border-white/8 bg-white/3 p-6 hover:bg-white/6 hover:border-white/15 transition-all">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500/15 border border-blue-500/20">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-white mb-1">{{ t('nav.flights') }}</h3>
                    <p class="text-sm text-slate-400">Bekijk beschikbare vluchten</p>
                </Link>

                <Link :href="route('vluchten.zoek')" class="group rounded-2xl border border-white/8 bg-white/3 p-6 hover:bg-white/6 hover:border-white/15 transition-all">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-yellow-400/10 border border-yellow-400/15">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-white mb-1">{{ t('nav.search') }}</h3>
                    <p class="text-sm text-slate-400">Zoek op datum en bestemming</p>
                </Link>

                <Link href="/profile" class="group rounded-2xl border border-white/8 bg-white/3 p-6 hover:bg-white/6 hover:border-white/15 transition-all">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/15">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-white mb-1">Profiel</h3>
                    <p class="text-sm text-slate-400">Beheer uw accountgegevens</p>
                </Link>
            </div>
        </div>
    </div>
</template>
