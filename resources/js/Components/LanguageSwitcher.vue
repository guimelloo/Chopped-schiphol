<script setup>
import { useI18n } from '@/composables/useI18n.js'

const { currentLang, setLang } = useI18n()

const langs = [
    { code: 'nl', label: 'NL', flag: '🇳🇱' },
    { code: 'en', label: 'EN', flag: '🇬🇧' },
    { code: 'pt', label: 'PT', flag: '🇧🇷' },
    { code: 'es', label: 'ES', flag: '🇪🇸' },
]
</script>

<template>
    <div class="flex items-center gap-1">
        <button
            v-for="lang in langs"
            :key="lang.code"
            @click="setLang(lang.code)"
            :title="lang.flag + ' ' + lang.label"
            :class="[
                'rounded px-2 py-1 text-xs font-bold transition-colors',
                currentLang === lang.code
                    ? 'bg-yellow-400 text-blue-900'
                    : 'text-white/70 hover:text-white hover:bg-white/10',
            ]"
        >
            {{ lang.label }}
        </button>
    </div>
</template>
