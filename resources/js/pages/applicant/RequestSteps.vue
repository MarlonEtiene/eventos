<script setup lang="ts">
import { computed } from 'vue'
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    modelValue: number
}>()

const emit = defineEmits(['update:modelValue'])

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

function goToStep(step: number) {
    currentStep.value = step
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
                @click="goToStep(step.id)"
                class="flex-1 px-4 py-2 rounded-lg border text-sm transition"
                :class="step.id === currentStep
          ? 'bg-blue-600 text-white border-blue-600'
          : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'
        "
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
                @click="goToStep(currentStep - 1)"
                class="px-4 py-2 rounded-lg border text-sm"
            >
                Voltar
            </button>

            <button
                v-if="currentStep < steps.length"
                @click="goToStep(currentStep + 1)"
                class="ml-auto px-4 py-2 rounded-lg bg-blue-600 text-white text-sm"
            >
                Próximo
            </button>
        </div>
        <button
            class="ml-auto px-4 py-2 rounded-lg bg-slate-600 text-white text-sm w-full"
            @click="router.visit(route('applicant.dashboard.index'))"
        >
            Voltar para Dashboard
        </button>
    </div>
</template>

