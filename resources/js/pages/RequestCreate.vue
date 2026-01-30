<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3';
import RequestSteps from '@/pages/applicant/RequestSteps.vue'
import InstitutionalHeader from "@/pages/partials/InstitutionalHeader.vue";
import ApplicantSection from "@/pages/applicant/Partials/ApplicantSection.vue";
import EventSection from "@/pages/applicant/Partials/EventSection.vue";
import CommunicationSection from "@/pages/applicant/Partials/CommunicationSection.vue";

const page = usePage()
const requestData = page.props.request_data
const readOnly = page.props.read_only

onMounted(() => {
    applyPrefill('start_at', page.props.prefillStart)
    applyPrefill('end_at', page.props.prefillEnd)
    applyPrefill('delivery_date', page.props.prefillDeliveryDate)
});

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
    email: requestData?.email ?? '',
    name: requestData?.name ?? '',
    function: requestData?.function ?? '',
    sector: requestData?.sector ?? '',
    phone: requestData?.phone ?? '',

    // decisão
    has_event: nullableBoolean(requestData?.has_event),

    // 2 - Evento
    title: requestData?.title ?? '',
    start_at: requestData?.start_at ?? '',
    end_at: requestData?.end_at ?? '',
    local_id: requestData?.local_id ?? null,
    target_audience: requestData?.target_audience ?? [] as string[],
    others_audience: requestData?.others_audience ?? '',
    estimated_audience: requestData?.estimated_audience ?? '',

    // 2.1 - Sobre o Evento
    objective: requestData?.objective ?? '',
    activities: requestData?.activities ?? '',
    resources: requestData?.resources ?? '',
    responsibles: requestData?.responsibles ?? '',

    // 2.2 - Itens especiais
    with_snack: nullableBoolean(requestData?.with_snack),
    snack_description: requestData?.snack_description ?? '',

    with_gift: nullableBoolean(requestData?.with_gift),
    gift_description: requestData?.gift_description ?? '',

    with_contribution: nullableBoolean(requestData?.with_contribution),
    contribution_description: requestData?.contribution_description ?? '',

    // 3 - Comunicação
    communication_type_id: requestData?.communication_type_id ?? '',
    communication_type_other: requestData?.communication_type_other ?? '',
    art_image_text: requestData?.art_image_text ?? '',
    delivery_date: requestData?.delivery_date ?? '',
    observations: requestData?.observations ?? '',

    attachments: [] as File[],

    // Ciente
    aware: Boolean(requestData?.aware) ?? false,
    // Declaração
    declaration: Boolean(requestData?.declaration) ?? false,
})

const canSubmit = computed(() => {
    return (
        form.has_event !== null &&
        form.declaration === true
    )
})

const stepFields: Record<number, string[]> = {
    1: ['name', 'email', 'function', 'sector', 'phone', 'has_event'],
    2: [
        'title',
        'start_at',
        'end_at',
        'local_id',
        'target_audience',
        'estimated_audience',
        'objective',
        'activities',
        'resources',
        'responsibles',
        'with_snack',
        'snack_description',
        'with_gift',
        'gift_description',
        'with_contribution',
        'contribution_description',
    ],
    3: [
        'communication_type_id',
        'communication_type_other',
        'art_image_text',
        'delivery_date',
        'aware'
    ],
}

const submitForm = () => {
    console.log(form.data());
    form.post(route('request.store'));
}

watch(
    () => page.props.errors,
    (errors) => {
        if (!errors || Object.keys(errors).length === 0) return

        // descobrir o primeiro step que contém erro
        const firstErrorStep = Object.entries(stepFields)
            .find(([_, fields]) =>
                fields.some(field => errors[field])
            )?.[0]

        if (firstErrorStep) {
            step.value = Number(firstErrorStep)
        }
    },
    { deep: true }
)

const errorSteps = computed(() => {
    const errors = page.props.errors || {}

    return Object.entries(stepFields)
        .filter(([step, fields]) => {
            const stepNumber = Number(step)

            // 🔑 IGNORA steps desabilitados
            if (disabledSteps.value.includes(stepNumber)) {
                return false
            }

            return fields.some(field => errors[field])
        })
        .map(([step]) => Number(step))
})

function handleEventDecision(value: boolean|null) {
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

function applyPrefill(field: keyof typeof form, value: string | null) {
    if (!requestData && value && !form[field]) {
        form[field] = value
    }
}

function nullableBoolean(value: unknown): boolean | null {
    if (value === null || value === undefined) return null
    return Boolean(value)
}

</script>

<template>
    <div class="min-h-screen bg-slate-100 p-4">
        <InstitutionalHeader
            title="Solicitação de Eventos"
            subtitle="Formulário Institucional"
            hospital="Hospital Municipal Albert Schweitzer"
            class="py-4 md:py-4"
        />

        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-4 space-y-4 mt-2">
            <div
                v-if="Object.keys($page.props.errors || {}).length"
                class="hidden md:block mt-4 p-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-700"
            >
                Existem erros no formulário. Verifique os campos destacados nos passos abaixo.
            </div>
            <RequestSteps
                v-model="step"
                :disabled-steps="disabledSteps"
                :flow-resolved="form.has_event !== null"
                :error-steps="errorSteps"
            >
                <template #step-1>
                    <ApplicantSection :form="form"
                                      @decide-event="handleEventDecision"
                                      :readonly="readOnly"
                    />
                </template>

                <template #step-2>
                    <EventSection :form="form"
                                  @decide-event="handleEventDecision"
                                  :readonly="readOnly"
                    />
                </template>

                <template #step-3>
                    <CommunicationSection :form="form"
                                          :readonly="readOnly"
                                          @decide-event="handleEventDecision" />
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
                        :disabled="readOnly"
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

                <button v-if="!readOnly"
                    @click="submitForm"
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
