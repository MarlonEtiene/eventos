<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import { formatters } from "@/composables/formatters";
import { useRoles } from "@/composables/useRoles";

import RequestSteps from '@/pages/applicant/RequestSteps.vue'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue'
import ApplicantSection from '@/pages/applicant/Partials/ApplicantSection.vue'
import EventSection from '@/pages/applicant/Partials/EventSection.vue'
import CommunicationSection from '@/pages/applicant/Partials/CommunicationSection.vue'

const { nullableBoolean, translateStatus } = formatters();
const { is } = useRoles();

const page = usePage()
const requestData = page.props.request_data as any;
const user = page.props.user as any;

const canDecide = computed(() => {
    if (is('directorship')) {
        return true;
    }

    return is('admin') &&
        nullableBoolean(requestData?.has_communication) === true &&
        nullableBoolean(requestData?.has_event) === false;
});

const form = useForm({
    // identificação
    email: requestData?.email ?? user?.email,
    name: requestData?.name ?? user?.name,
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


const step = ref(1)

// define steps desabilitados com base no fluxo real
const disabledSteps = computed(() => {
    const steps: number[] = []

    if (!requestData.has_event) {
        steps.push(2)
    }

    if (!requestData.has_communication) {
        steps.push(3)
    }

    return steps
})
</script>

<template>
    <div class="min-h-screen bg-slate-100 p-4">
        <InstitutionalHeader
            title="Visualização da Solicitação"
            subtitle="Painel Administrativo"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-4 mt-4 space-y-4">

            <!-- STATUS -->
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-700">
                        Status da solicitação
                    </h2>
                    <span
                        class="inline-block mt-1 px-3 py-1 rounded-full text-lg font-semibold"
                        :class="{
                            'bg-blue-100 text-blue-700': requestData.status === 'sended',
                            'bg-green-100 text-green-700': requestData.status === 'approved',
                            'bg-red-100 text-red-700': requestData.status === 'rejected',
                        }"
                    >
                        {{ translateStatus(requestData.status) }}
                    </span>
                </div>
            </div>

            <!-- STEPS -->
            <RequestSteps
                v-model="step"
                :disabled-steps="disabledSteps"
                :flow-resolved="true"
            >
                <template #step-1>
                    <ApplicantSection
                        :form="form"
                        readonly
                    />
                </template>

                <template #step-2>
                    <EventSection
                        v-if="requestData.has_event"
                        :form="form"
                        readonly
                    />
                </template>

                <template #step-3>
                    <CommunicationSection
                        :form="form"
                        readonly
                        :request-data="requestData"
                    />
                </template>
            </RequestSteps>

            <!-- AÇÕES ADMIN -->
            <div class="flex gap-3 pt-4 border-t">
                <!-- ESQUERDA -->
                <button
                    class="px-4 py-2 md:px-6 md:py-3 rounded-lg bg-slate-600 text-white text-sm mr-auto"
                    @click="router.visit(route('admin.dashboard.index'))"
                >
                    Dashboard
                </button>

                <!-- REPROVAR -->
                <button
                    :disabled="!canDecide"
                    class="px-4 py-2 rounded-lg text-sm transition"
                    :class="canDecide
                        ? 'bg-red-600 text-white hover:bg-red-700'
                        : 'bg-red-300 text-white cursor-not-allowed opacity-60'"
                >
                    Reprovar
                </button>

                <!-- APROVAR -->
                <button
                    :disabled="!canDecide"
                    class="px-4 py-2 rounded-lg text-sm transition"
                    :class="canDecide
                        ? 'bg-green-600 text-white hover:bg-green-700'
                        : 'bg-green-300 text-white cursor-not-allowed opacity-60'"
                >
                    Aprovar
                </button>
            </div>

        </div>
    </div>
</template>
