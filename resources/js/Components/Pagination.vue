<script setup lang="ts">
import { router } from '@inertiajs/vue3'

const props = defineProps<{
    links: {
        url: string | null
        label: string
        active: boolean
    }[]
}>()

function go(url: string | null) {
    if (!url) return

    router.get(url, {}, {
        preserveState: true,
        preserveScroll: true,
    })
}
</script>

<template>
    <nav
        v-if="links.length > 3"
        class="flex items-center justify-between px-6 py-4 border-t text-sm"
    >
        <!-- ESQUERDA -->
        <div class="text-slate-500">

        </div>

        <!-- DIREITA -->
        <div class="flex items-center gap-1">
            <button
                v-for="(link, index) in links"
                :key="index"
                v-html="link.label"
                :disabled="!link.url"
                @click="go(link.url)"
                class="min-w-[36px] h-9 px-2 rounded-lg border text-sm transition"
                :class="[
                    link.active
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white text-slate-700 hover:bg-slate-100',
                    !link.url && 'opacity-40 cursor-not-allowed'
                ]"
            />
        </div>
    </nav>
</template>
