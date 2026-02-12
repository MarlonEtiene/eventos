<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Administração" />

    <div class="min-h-screen bg-slate-100">
        <!-- Header -->
        <header class="bg-sky-100 border-b">
            <div class="max-w-7xl mx-auto px-6 py-6 text-center">
                <h1 class="text-xl font-semibold text-slate-800">
                    Solicitações
                </h1>

                <p class="text-sm text-slate-600">
                    Administração · Hospital Municipal Albert Schweitzer
                </p>
            </div>
        </header>

        <!-- Conteúdo -->
        <main class="max-w-7xl mx-auto px-6 py-8">
            <slot />
        </main>
        <footer class="mt-2 pb-6 text-center">
            <span class="text-base font-semibold text-sky-800 tracking-wide">e-Eventos</span>
            <img src="/images/logo-eei.png"
                 alt="Viva Rio Logo"
                 class="h-14 mt-1 block mx-auto" />
        </footer>
    </div>
</template>
