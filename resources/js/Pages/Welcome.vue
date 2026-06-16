<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import { useI18n } from '@/composables/useI18n.js'
import { ref, onMounted, onUnmounted } from 'vue'

const { t } = useI18n()

const departures = ref([
    { time: '06:15', dest: 'London Heathrow', code: 'LHR', airline: 'KLM', flight: 'KL1017', gate: 'D43', status: 'gepland' },
    { time: '07:30', dest: 'Paris CDG', code: 'CDG', airline: 'Air France', flight: 'AF1241', gate: 'B18', status: 'gepland' },
    { time: '08:45', dest: 'New York JFK', code: 'JFK', airline: 'Delta', flight: 'DL9358', gate: 'E12', status: 'vertrokken' },
    { time: '09:20', dest: 'Dubai DXB', code: 'DXB', airline: 'Emirates', flight: 'EK149', gate: 'G44', status: 'gepland' },
    { time: '10:00', dest: 'Barcelona BCN', code: 'BCN', airline: 'Vueling', flight: 'VY8340', gate: 'H7', status: 'gepland' },
    { time: '10:45', dest: 'Berlin BER', code: 'BER', airline: 'KLM', flight: 'KL1775', gate: 'C22', status: 'vertraging' },
    { time: '11:30', dest: 'Tokyo NRT', code: 'NRT', airline: 'Japan Air', flight: 'JL412', gate: 'F31', status: 'gepland' },
])

const statusConfig = {
    gepland:   { cls: 'text-emerald-400', dot: 'bg-emerald-400', pulse: true },
    vertrokken: { cls: 'text-sky-400',    dot: 'bg-sky-400',     pulse: false },
    vertraging: { cls: 'text-amber-400',  dot: 'bg-amber-400',   pulse: true },
    geannuleerd:{ cls: 'text-red-400',    dot: 'bg-red-400',     pulse: false },
}

const now = ref(new Date())
let timer
onMounted(() => { timer = setInterval(() => { now.value = new Date() }, 1000) })
onUnmounted(() => clearInterval(timer))

const clock = ref(null)
const updateClock = () => {
    if (clock.value) {
        clock.value.textContent = new Date().toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
    }
}

const searchForm = ref({ van: '', naar: '', datum: '', klasse: '' })
function zoeken() {
    router.get('/vluchten/zoek', searchForm.value)
}

const mobileOpen = ref(false)

const features = [
    {
        icon: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
        nl: 'Bestemmingen wereldwijd', en: 'Global destinations', pt: 'Destinos mundiais', es: 'Destinos mundiales',
        desc_nl: '350+ non-stop routes vanuit Amsterdam', desc_en: '350+ non-stop routes from Amsterdam', desc_pt: '350+ rotas diretas de Amsterdã', desc_es: '350+ rutas directas desde Ámsterdam',
    },
    {
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        nl: 'Realtime informatie', en: 'Real-time info', pt: 'Informação em tempo real', es: 'Información en tiempo real',
        desc_nl: 'Live vluchtstatussen en gate-updates', desc_en: 'Live flight statuses and gate updates', desc_pt: 'Status de voo ao vivo e atualizações de portão', desc_es: 'Estados de vuelo en vivo y actualizaciones de puerta',
    },
    {
        icon: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
        nl: 'Eenvoudig boeken', en: 'Easy booking', pt: 'Reserva fácil', es: 'Reserva sencilla',
        desc_nl: 'In 3 stappen uw vlucht geboekt', desc_en: 'Book your flight in 3 steps', desc_pt: 'Reserve seu voo em 3 passos', desc_es: 'Reserve su vuelo en 3 pasos',
    },
    {
        icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        nl: 'Veilig & betrouwbaar', en: 'Safe & reliable', pt: 'Seguro e confiável', es: 'Seguro y fiable',
        desc_nl: 'Certificeerde luchtvaartstandaarden', desc_en: 'Certified aviation standards', desc_pt: 'Padrões de aviação certificados', desc_es: 'Estándares de aviación certificados',
    },
]
</script>

<template>
    <Head title="Chopped Schiphol — Amsterdam Airport" />

    <div class="min-h-screen bg-slate-950 text-white overflow-x-hidden">

        <!-- ═══ NAVBAR ═══ -->
        <nav class="fixed top-0 inset-x-0 z-50 border-b border-white/5 backdrop-blur-xl bg-slate-950/80">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Brand -->
                    <Link href="/" class="flex items-center gap-2.5 group shrink-0">
                        <div class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-yellow-400 shadow-lg shadow-yellow-400/30 group-hover:shadow-yellow-400/50 transition-shadow">
                            <svg class="h-5 w-5 text-slate-950" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col leading-none">
                            <span class="text-xs font-bold tracking-[0.2em] text-yellow-400 uppercase">Chopped</span>
                            <span class="text-sm font-extrabold tracking-wide text-white">Schiphol</span>
                        </div>
                    </Link>

                    <!-- Desktop nav links -->
                    <div class="hidden md:flex items-center gap-1">
                        <Link :href="route('vluchten.index')" class="px-4 py-2 rounded-lg text-sm font-medium text-white/70 hover:text-white hover:bg-white/5 transition-all">
                            {{ t('nav.flights') }}
                        </Link>
                        <Link :href="route('vluchten.zoek')" class="px-4 py-2 rounded-lg text-sm font-medium text-white/70 hover:text-white hover:bg-white/5 transition-all">
                            {{ t('nav.search') }}
                        </Link>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center gap-3">
                        <LanguageSwitcher />
                        <div class="hidden md:flex items-center gap-2">
                            <Link :href="route('coordinator.login')" class="rounded-lg bg-yellow-400 px-4 py-2 text-sm font-bold text-slate-950 hover:bg-yellow-300 transition-colors shadow-md shadow-yellow-400/20">
                                {{ t('nav.coordinator') }}
                            </Link>
                            <Link href="/directeur/inloggen" class="rounded-lg border border-white/20 px-4 py-2 text-sm font-medium text-white/80 hover:bg-white/5 hover:border-white/30 transition-all">
                                {{ t('nav.director') }}
                            </Link>
                        </div>
                        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-white/5 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div v-if="mobileOpen" class="md:hidden border-t border-white/5 bg-slate-950 px-4 py-4 space-y-2">
                <Link :href="route('vluchten.index')" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-white/5" @click="mobileOpen = false">{{ t('nav.flights') }}</Link>
                <Link :href="route('vluchten.zoek')" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-white/5" @click="mobileOpen = false">{{ t('nav.search') }}</Link>
                <div class="pt-2 border-t border-white/5 flex flex-col gap-2">
                    <Link :href="route('coordinator.login')" class="block text-center rounded-lg bg-yellow-400 px-4 py-2.5 text-sm font-bold text-slate-950">{{ t('nav.coordinator') }}</Link>
                    <Link href="/directeur/inloggen" class="block text-center rounded-lg border border-white/20 px-4 py-2.5 text-sm font-medium">{{ t('nav.director') }}</Link>
                </div>
            </div>
        </nav>

        <!-- ═══ HERO ═══ -->
        <section class="relative min-h-screen flex flex-col items-center justify-center pt-16 overflow-hidden">
            <!-- Background layers -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-blue-950/60 to-slate-950"></div>
            <div class="absolute inset-0" style="background-image: radial-gradient(ellipse at 20% 40%, rgba(59,130,246,0.12) 0%, transparent 60%), radial-gradient(ellipse at 80% 10%, rgba(234,179,8,0.07) 0%, transparent 50%), radial-gradient(ellipse at 50% 80%, rgba(14,165,233,0.06) 0%, transparent 50%)"></div>
            <!-- Grid pattern -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 64px 64px;"></div>

            <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex flex-col items-center text-center">
                <!-- Badge -->
                <div class="mb-8 inline-flex items-center gap-2 rounded-full border border-yellow-400/30 bg-yellow-400/10 px-5 py-2 text-sm font-semibold text-yellow-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                    Amsterdam Airport Schiphol (AMS) — {{ t('welcome.live') }}
                </div>

                <!-- Big title -->
                <h1 class="text-5xl sm:text-7xl lg:text-8xl font-black tracking-tighter mb-4 leading-none">
                    <span class="text-yellow-400">CHOPPED</span>
                    <br />
                    <span class="text-white">SCHIPHOL</span>
                </h1>

                <p class="mt-4 text-xl sm:text-2xl text-blue-200/80 font-light max-w-2xl leading-relaxed">
                    {{ t('welcome.subtitle') }}
                </p>

                <!-- Search widget -->
                <div class="mt-12 w-full max-w-3xl">
                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-2">
                        <form @submit.prevent="zoeken" class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                                    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                </div>
                                <input v-model="searchForm.naar" type="text" :placeholder="t('search.to')" class="w-full rounded-xl bg-white/8 pl-9 pr-3 py-3.5 text-sm text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/50 border border-white/5 focus:border-yellow-400/30 transition-all" />
                            </div>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                                    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input v-model="searchForm.datum" type="date" class="w-full rounded-xl bg-white/8 pl-9 pr-3 py-3.5 text-sm text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/50 border border-white/5 focus:border-yellow-400/30 transition-all [color-scheme:dark]" />
                            </div>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                                    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                </div>
                                <select v-model="searchForm.klasse" class="w-full rounded-xl bg-white/8 pl-9 pr-3 py-3.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-yellow-400/50 border border-white/5 focus:border-yellow-400/30 transition-all appearance-none">
                                    <option value="" class="bg-slate-900">{{ t('search.allClasses') }}</option>
                                    <option value="economy" class="bg-slate-900">Economy</option>
                                    <option value="business" class="bg-slate-900">Business</option>
                                </select>
                            </div>
                            <button type="submit" class="rounded-xl bg-yellow-400 px-5 py-3.5 text-sm font-bold text-slate-950 hover:bg-yellow-300 active:scale-95 transition-all shadow-lg shadow-yellow-400/25 flex items-center justify-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                {{ t('search.searchBtn') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Secondary CTAs -->
                <div class="mt-6 flex flex-wrap gap-3 justify-center">
                    <Link :href="route('vluchten.index')" class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-6 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 hover:text-white transition-all">
                        {{ t('welcome.viewFlights') }}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Scroll hint -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-white/30">
                <span class="text-xs uppercase tracking-widest">Scroll</span>
                <svg class="h-4 w-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </section>

        <!-- ═══ STATS ═══ -->
        <section class="relative border-y border-white/5 bg-gradient-to-r from-blue-950/40 via-slate-900/60 to-blue-950/40">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                    <div class="group">
                        <p class="text-4xl font-black text-yellow-400 group-hover:scale-105 transition-transform">350<span class="text-yellow-300/70">+</span></p>
                        <p class="mt-1.5 text-sm text-blue-300/70 font-medium">{{ t('welcome.stat1') }}</p>
                    </div>
                    <div class="group">
                        <p class="text-4xl font-black text-yellow-400 group-hover:scale-105 transition-transform">90<span class="text-yellow-300/70">+</span></p>
                        <p class="mt-1.5 text-sm text-blue-300/70 font-medium">{{ t('welcome.stat2') }}</p>
                    </div>
                    <div class="group">
                        <p class="text-4xl font-black text-yellow-400 group-hover:scale-105 transition-transform">71<span class="text-xl">M</span></p>
                        <p class="mt-1.5 text-sm text-blue-300/70 font-medium">{{ t('welcome.stat3') }}</p>
                    </div>
                    <div class="group">
                        <p class="text-4xl font-black text-yellow-400 group-hover:scale-105 transition-transform">24<span class="text-xl">/7</span></p>
                        <p class="mt-1.5 text-sm text-blue-300/70 font-medium">{{ t('welcome.stat4') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ DEPARTURE BOARD ═══ -->
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20">
            <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-xs font-semibold uppercase tracking-widest text-emerald-400">Live</span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-white">{{ t('welcome.departures') }}</h2>
                    <p class="text-blue-400/70 text-sm mt-1">Amsterdam Schiphol (AMS)</p>
                </div>
                <Link :href="route('vluchten.index')" class="flex items-center gap-2 text-sm font-semibold text-yellow-400 hover:text-yellow-300 transition-colors">
                    {{ t('welcome.viewFlights') }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <!-- FIDS Board -->
            <div class="overflow-hidden rounded-2xl border border-white/8 bg-slate-900/60 backdrop-blur-sm shadow-2xl shadow-black/40">
                <!-- Board header -->
                <div class="grid grid-cols-12 gap-2 border-b border-white/8 bg-slate-800/40 px-5 py-3">
                    <span class="col-span-2 text-xs font-bold uppercase tracking-widest text-slate-400">{{ t('flight.departure') }}</span>
                    <span class="col-span-4 text-xs font-bold uppercase tracking-widest text-slate-400">{{ t('welcome.destination') }}</span>
                    <span class="col-span-2 text-xs font-bold uppercase tracking-widest text-slate-400">{{ t('flight.airline') }}</span>
                    <span class="col-span-2 text-xs font-bold uppercase tracking-widest text-slate-400">Gate</span>
                    <span class="col-span-2 text-xs font-bold uppercase tracking-widest text-slate-400">{{ t('flight.status') }}</span>
                </div>

                <div>
                    <div
                        v-for="(dep, i) in departures"
                        :key="dep.flight"
                        :class="['grid grid-cols-12 gap-2 items-center px-5 py-4 transition-colors hover:bg-white/3', i % 2 === 0 ? 'bg-transparent' : 'bg-white/[0.02]', i < departures.length - 1 ? 'border-b border-white/5' : '']"
                    >
                        <span class="col-span-2 text-2xl font-black text-white tabular-nums tracking-tight">{{ dep.time }}</span>
                        <div class="col-span-4">
                            <p class="font-bold text-white">{{ dep.dest }}</p>
                            <p class="text-xs text-slate-400 mt-0.5 font-mono">{{ dep.flight }} · {{ dep.code }}</p>
                        </div>
                        <span class="col-span-2 text-sm text-slate-300 font-medium">{{ dep.airline }}</span>
                        <span class="col-span-2">
                            <span class="inline-flex items-center justify-center h-7 w-14 rounded-lg bg-white/8 border border-white/10 text-sm font-bold text-white font-mono">
                                {{ dep.gate }}
                            </span>
                        </span>
                        <span class="col-span-2">
                            <span class="inline-flex items-center gap-1.5 text-sm font-semibold" :class="statusConfig[dep.status]?.cls || 'text-slate-400'">
                                <span class="h-1.5 w-1.5 rounded-full shrink-0" :class="[statusConfig[dep.status]?.dot || 'bg-slate-400', statusConfig[dep.status]?.pulse ? 'animate-pulse' : '']"></span>
                                {{ t('flight.statuses.' + dep.status) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ PORTAL CARDS ═══ -->
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold text-white">{{ t('welcome.portals') }}</h2>
                <p class="mt-2 text-blue-300/60 text-sm">{{ t('welcome.portalsSubtitle') }}</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-3">
                <!-- Passenger -->
                <Link :href="route('vluchten.zoek')" class="group relative overflow-hidden rounded-2xl border border-white/8 bg-gradient-to-br from-blue-600/20 via-blue-700/10 to-transparent p-7 hover:border-blue-500/30 hover:from-blue-600/30 transition-all duration-300">
                    <div class="absolute top-0 right-0 h-40 w-40 translate-x-10 -translate-y-10 rounded-full bg-blue-500/8 blur-2xl group-hover:bg-blue-500/15 transition-colors"></div>
                    <div class="relative">
                        <div class="mb-5 inline-flex h-13 w-13 items-center justify-center rounded-xl bg-blue-500/20 border border-blue-500/20">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ t('welcome.passengerPortal') }}</h3>
                        <p class="text-sm text-blue-200/60 leading-relaxed">{{ t('welcome.passengerDesc') }}</p>
                        <div class="mt-5 flex items-center gap-2 text-sm font-semibold text-blue-400 group-hover:text-blue-300 transition-colors">
                            {{ t('welcome.viewFlights') }}
                            <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </Link>

                <!-- Coordinator -->
                <Link :href="route('coordinator.login')" class="group relative overflow-hidden rounded-2xl border border-yellow-400/15 bg-gradient-to-br from-yellow-400/15 via-yellow-500/8 to-transparent p-7 hover:border-yellow-400/30 hover:from-yellow-400/25 transition-all duration-300">
                    <div class="absolute top-0 right-0 h-40 w-40 translate-x-10 -translate-y-10 rounded-full bg-yellow-400/10 blur-2xl group-hover:bg-yellow-400/20 transition-colors"></div>
                    <div class="relative">
                        <div class="mb-5 inline-flex h-13 w-13 items-center justify-center rounded-xl bg-yellow-400/20 border border-yellow-400/20">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ t('welcome.coordinatorLogin') }}</h3>
                        <p class="text-sm text-blue-200/60 leading-relaxed">{{ t('welcome.coordinatorDesc') }}</p>
                        <div class="mt-5 flex items-center gap-2 text-sm font-semibold text-yellow-400 group-hover:text-yellow-300 transition-colors">
                            {{ t('nav.login') }}
                            <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </Link>

                <!-- Director -->
                <Link href="/directeur/inloggen" class="group relative overflow-hidden rounded-2xl border border-white/8 bg-gradient-to-br from-slate-800/60 via-slate-800/30 to-transparent p-7 hover:border-white/15 hover:from-slate-700/40 transition-all duration-300">
                    <div class="absolute top-0 right-0 h-40 w-40 translate-x-10 -translate-y-10 rounded-full bg-slate-600/10 blur-2xl group-hover:bg-slate-600/20 transition-colors"></div>
                    <div class="relative">
                        <div class="mb-5 inline-flex h-13 w-13 items-center justify-center rounded-xl bg-white/8 border border-white/10">
                            <svg class="h-6 w-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ t('welcome.directorLogin') }}</h3>
                        <p class="text-sm text-blue-200/60 leading-relaxed">{{ t('welcome.directorDesc') }}</p>
                        <div class="mt-5 flex items-center gap-2 text-sm font-semibold text-slate-400 group-hover:text-slate-300 transition-colors">
                            {{ t('nav.login') }}
                            <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </Link>
            </div>
        </section>

        <!-- ═══ FEATURES ═══ -->
        <section class="border-t border-white/5 bg-gradient-to-b from-slate-900/40 to-transparent">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20">
                <div class="mb-10 text-center">
                    <h2 class="text-3xl font-extrabold text-white">{{ t('welcome.whyChopped') }}</h2>
                </div>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="feat in features" :key="feat.nl" class="group rounded-xl border border-white/6 bg-white/3 p-6 hover:bg-white/6 hover:border-white/12 transition-all">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-yellow-400/10 border border-yellow-400/15 group-hover:bg-yellow-400/20 transition-colors">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="feat.icon" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-white text-sm mb-1">{{ feat.nl }}</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">{{ feat.desc_nl }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ FOOTER ═══ -->
        <footer class="border-t border-white/5 bg-slate-950">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-400">
                            <svg class="h-4 w-4 text-slate-950" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Chopped Schiphol</p>
                            <p class="text-xs text-slate-500">Amsterdam Airport</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6 text-xs text-slate-500">
                        <Link :href="route('vluchten.index')" class="hover:text-slate-300 transition-colors">{{ t('nav.flights') }}</Link>
                        <Link :href="route('vluchten.zoek')" class="hover:text-slate-300 transition-colors">{{ t('nav.search') }}</Link>
                        <Link :href="route('coordinator.login')" class="hover:text-slate-300 transition-colors">{{ t('nav.coordinator') }}</Link>
                        <Link href="/directeur/inloggen" class="hover:text-slate-300 transition-colors">{{ t('nav.director') }}</Link>
                    </div>
                    <p class="text-xs text-slate-600">&copy; {{ new Date().getFullYear() }} Chopped Schiphol</p>
                </div>
            </div>
        </footer>
    </div>
</template>
