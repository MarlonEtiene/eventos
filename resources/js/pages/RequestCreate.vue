<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3';
import RequestSteps from '@/pages/applicant/RequestSteps.vue'
import InstitutionalHeader from "@/pages/partials/InstitutionalHeader.vue";
import ApplicantSection from "@/pages/applicant/Partials/ApplicantSection.vue";
import EventSection from "@/pages/applicant/Partials/EventSection.vue";

const step = ref(1)
//const disabledSteps = ref<number[]>([])
const flowResolved = ref(false)

const disabledSteps = computed(() => {
    // Ainda não decidiu se há evento
    if (form.has_event === null) {
        return [2, 3]
    }

    // Decidiu que NÃO há evento
    if (form.has_event === false) {
        return [2]
    }

    // Decidiu que HÁ evento
    return []
})

const form = useForm({
    // identificação
    email: '',
    name: '',
    function: '',
    sector: '',
    phone: '',

    // decisão
    has_event: null,

    // 2 - Evento
    title: '',
    start_at: '',
    end_at: '',
    location: null,
    target_audience: [] as string[],
    estimated_audience: '',
    // 2.1 - Sobre o Evento
    objective: '',
    activities: '',
    resources: '',
    responsibles: '',
    // 2.2 - Itens especiais
    with_snack: null as null | boolean,
    snack_description: '',
    with_gift: null as null | boolean,
    gift_description: '',
    with_contribution: null as null | boolean,
    contribution_description: '',

    // 3 - Comunicação
    communication_type: '',
    message: '',

    // Declaração
    declaration: false,
})

const canSubmit = computed(() => {
    return (
        form.has_event !== null &&
        form.declaration === true
    )
})

function handleEventDecision(value: boolean) {
    form.has_event = value
    flowResolved.value = true

    if (value) {
        disabledSteps.value = []
        step.value = 2
    } else {
        disabledSteps.value = [2]
        step.value = 3
    }
}
</script>

<template>
    <div class="min-h-screen bg-slate-100 p-4">
        <InstitutionalHeader
            title="Solicitação de Eventos"
            subtitle="Formulário Institucional"
            hospital="Hospital Municipal Albert Schweitzer"
            class="py-4 md:py-8"
        />

        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-4 space-y-4 mt-2">
            <RequestSteps
                v-model="step"
                :disabled-steps="disabledSteps"
                :flow-resolved="form.has_event !== null"
            >
                <template #step-1>
                    <ApplicantSection :form="form"
                                      @decide-event="handleEventDecision"
                    />
                </template>

                <template #step-2>
                    <EventSection :form="form"
                                   @decide-event="handleEventDecision"/>
                </template>

                <template #step-3>
                    <CommunicationSection />
                </template>
            </RequestSteps>
            <section
                v-if="form.has_event !== null"
                class="mt-6 p-4 bg-slate-50 rounded-lg border space-y-2"
            >
                <h2 class="font-semibold text-slate-700">
                    Declaração do Solicitante
                </h2>
                <label class="flex items-start gap-2 text-sm text-slate-700">
                    <input
                        type="checkbox"
                        v-model="form.declaration"
                        class="mt-1"
                    />
                    Declaro que as informações prestadas são verdadeiras e que o evento/comunicação só será realizado mediante autorização da Direção.
                </label>
            </section>

            <div class="mt-6 flex justify-end">
                <button
                    class="px-4 py-2 md:px-6 md:py-3 rounded-lg bg-slate-600 text-white text-sm"
                    @click="router.visit(route('applicant.dashboard.index'))"
                >
                    Dashboard
                </button>

                <button
                    type="submit"
                    :disabled="!canSubmit"
                    class="ml-auto px-4 py-2 md:px-6 md:py-3 rounded-lg text-white transition
                        bg-green-600 disabled:bg-green-300 disabled:cursor-not-allowed"
                >
                    Salvar&nbsp;&nbsp;
                    <fai icon="fa-solid fa-floppy-disk" size="lg" class="text-white" />
                </button>
            </div>
        </div>
    </div>
</template>
<style scoped>
/* Tailwind v4 compatible: sem @apply com cores */
.input {
    width: 100%;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--tw-border-color, #d1d5db);
}

.input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); /* blue-500 */
}
</style>
