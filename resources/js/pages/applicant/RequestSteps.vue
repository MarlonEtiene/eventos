<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
    modelValue: number
    disabledSteps?: number[]
    flowResolved?: boolean
}>()

const emit = defineEmits(['update:modelValue'])
const isDisabled = (stepId: number) =>
    props.disabledSteps?.includes(stepId)

const steps = [
    { id: 1, label: 'Identificação' },
    { id: 2, label: 'Evento' },
    { id: 3, label: 'Comunicação' },
]

const currentStep = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
})

const current = computed(() =>
    steps.find(s => s.id === currentStep.value)
)

const canShowNext = computed(() => {
    // Step 1 → só se o fluxo já foi decidido
    if (currentStep.value === 1) {
        return props.flowResolved
    }

    // Outros steps → comportamento normal
    return currentStep.value < steps.length
})

function goToStep(step: number) {
    if (isDisabled(step)) return
    currentStep.value = step
}

function nextStep() {
    const next = [...steps]
        .map(s => s.id)
        .find(id => id > currentStep.value && !isDisabled(id))

    if (next) {
        goToStep(next)
    }
}

function previousStep() {
    const prev = [...steps]
        .map(s => s.id)
        .filter(id => id < currentStep.value && !isDisabled(id))
        .pop()

    if (prev) {
        goToStep(prev)
    }
}
</script>

<template>
    <div class="w-full space-y-4">
        <!-- MOBILE STEPPER -->
        <div class="block md:hidden space-y-2">
            <!-- Dots -->
            <div class="flex items-center justify-center gap-3">
                <span
                    v-for="step in steps"
                    :key="step.id"
                    class="h-2 w-2 rounded-full transition"
                    :class="step.id === currentStep
                    ? 'bg-blue-600 scale-125'
                    : 'bg-gray-300'
                  "
                />
            </div>

            <!-- Label -->
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    Passo {{ currentStep }} de {{ steps.length }}
                </p>
                <p class="font-semibold text-gray-800">
                    {{ current?.label }}
                </p>
            </div>
        </div>

        <!-- DESKTOP TABS -->
        <div class="hidden md:flex gap-2">
            <button
                v-for="step in steps"
                :key="step.id"
                @click="!isDisabled(step.id) && goToStep(step.id)"
                class="flex-1 px-4 py-2 rounded-lg border text-sm transition"
                :class="[
                    step.id === currentStep
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50',
                    isDisabled(step.id) && 'opacity-40 cursor-not-allowed pointer-events-none'
                ]"
            >
                {{ step.label }}
                <span class="block text-xs opacity-70">
                    Passo {{ step.id }}
                </span>
            </button>
        </div>

        <!-- CONTEÚDO -->
        <div class="bg-white rounded-lg border p-4">
            <slot v-if="currentStep === 1" name="step-1" />
            <slot v-if="currentStep === 2" name="step-2" />
            <slot v-if="currentStep === 3" name="step-3" />
        </div>

        <!-- NAVEGAÇÃO -->
        <div class="flex justify-between">
            <button
                v-if="currentStep > 1"
                @click="previousStep"
                class="px-4 py-2 md:px-6 md:py-3 rounded-lg bg-gray-300 border text-sm"
            >
                <fai icon="fa-solid fa-circle-chevron-left" size="lg" class="text-black" />&nbsp;&nbsp; Voltar
            </button>

            <button
                v-if="canShowNext"
                @click="nextStep"
                class="ml-auto px-4 py-2 md:px-6 md:py-3 rounded-lg bg-blue-600 text-white text-sm disabled:opacity-40"
            >
                Próximo&nbsp;&nbsp; <fai icon="fa-solid fa-circle-chevron-right" size="lg" class="text-white" />
            </button>
        </div>
    </div>
</template>

