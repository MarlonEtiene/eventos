<script setup lang="ts">
const props = defineProps<{
    title: string
    subtitle?: string
    hospital?: string
    showQr?: boolean
    qrUrl?: string
}>()
</script>

<template>
    <header class="w-full bg-[#e9f1ff] border-b border-gray-300">
        <div class="max-w-5xl mx-auto px-4 py-6 flex flex-col items-center gap-4">

            <!-- Logo + Secretaria -->
            <div class="flex flex-col items-center">
                <img
                    src="/images/AlbertSchweitzer_Cor_Stroke.png"
                    alt="Prefeitura do Rio"
                    class="h-14 object-contain"
                />
                <span class="text-sm font-semibold text-sky-800 tracking-wide"></span>
            </div>

            <!-- Títulos -->
            <div class="text-center">
                <h1 class="text-2xl font-semibold text-gray-800">
                    {{ title }}
                </h1>

                <p v-if="subtitle" class="text-sm text-gray-600 mt-1">
                    {{ subtitle }}
                </p>

                <p v-if="hospital" class="text-sm text-gray-700 font-medium mt-1">
                    {{ hospital }}
                </p>
            </div>

            <!-- QR Code opcional -->
            <div v-if="showQr" class="mt-2">
                <img
                    :src="qrUrl"
                    alt="QR Code"
                    class="h-20 w-20 object-contain"
                />
            </div>

        </div>
    </header>
</template>
