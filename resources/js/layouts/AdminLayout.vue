<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Administração" />

    <div class="min-h-screen bg-slate-100">
        <!-- Header -->
        <header class="bg-sky-100 border-b">
            <div class="max-w-7xl mx-auto px-6 py-6 text-center">
                <img
                    src="/images/logo-eei.png"
                    class="h-12 mx-auto mb-2"
                    alt="Logo"
                />

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
    </div>
</template>
