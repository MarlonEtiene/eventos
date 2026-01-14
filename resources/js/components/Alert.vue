<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import {
    ShieldExclamationIcon,
    ShieldCheckIcon,
    XCircleIcon,
    InformationCircleIcon
} from '@heroicons/vue/24/solid'

const props = defineProps({
    message: {
        type: String,
        default: ''
    },
    type: {
        type: String,
        default: ''
    }
})

const page = usePage()

/**
 * Fonte da mensagem:
 * 1️⃣ prop (se vier)
 * 2️⃣ flash do Inertia
 */
const flashMessage = computed(() => {
    return props.message || page.props.flash?.message || page.props.flash?.status || ''
})

const flashType = computed(() => {
    return props.type || page.props.flash?.type || 'success'
})

const showAlert = ref(false)
const alertTitle = ref('')

const setTitle = () => {
    switch (flashType.value) {
        case 'warning':
            alertTitle.value = 'Atenção!'
            break
        case 'success':
            alertTitle.value = 'Sucesso!'
            break
        case 'error':
            alertTitle.value = 'Erro!'
            break
        case 'info':
            alertTitle.value = 'Informação!'
            break
        default:
            alertTitle.value = 'Sucesso!'
    }
}

const forceClose = () => {
    showAlert.value = false
}

const openAlert = () => {
    if (!flashMessage.value) return

    setTitle()
    showAlert.value = true

    setTimeout(() => {
        showAlert.value = false
    }, 8000)
}

onMounted(openAlert)

/**
 * Reage a novas mensagens (redirects Inertia)
 */
watch(flashMessage, () => {
    openAlert()
})
</script>

<template>
    <div
        class="absolute top-0 right-0 m-3 w-2/3 md:w-auto z-[999]"
        v-if="flashMessage && showAlert"
    >
        <div class="relative w-auto my-6 mx-auto max-w-6xl">
            <div
                class="border-t-4 rounded-b px-4 py-3 shadow-md"
                :class="{
                    'bg-orange-200 border-orange-500': flashType === 'warning',
                    'bg-green-200 border-green-500': flashType === 'success',
                    'bg-red-200 border-red-500': flashType === 'error',
                    'bg-blue-200 border-blue-500': flashType === 'info'
                }"
            >
                <div class="flex">
                    <div class="px-2 flex-shrink-0">
                        <ShieldCheckIcon class="h-7 w-7 text-green-600" v-if="flashType === 'success'" />
                        <ShieldExclamationIcon class="h-7 w-7 text-orange-600" v-if="flashType === 'warning'" />
                        <XCircleIcon class="h-7 w-7 text-red-600" v-if="flashType === 'error'" />
                        <InformationCircleIcon class="h-7 w-7 text-blue-600" v-if="flashType === 'info'" />
                    </div>

                    <div class="flex-grow">
                        <p class="font-bold">{{ alertTitle }}</p>
                        <p class="text-sm" v-html="flashMessage"></p>
                    </div>

                    <span
                        class="px-2 cursor-pointer flex-shrink-0"
                        @click="forceClose"
                    >
                        <svg class="fill-current h-6 w-6 text-black"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
