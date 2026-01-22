<script setup lang="ts">
import { ref } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3';
import RequestSteps from '@/Pages/Applicant/RequestSteps.vue'
import InstitutionalHeader from "@/Pages/Partials/InstitutionalHeader.vue";
import ApplicantSection from "@/Pages/Applicant/Partials/ApplicantSection.vue";

const step = ref(1)

const form = useForm({
    // identificação
    name: '',
    sector: '',
    phone: '',
    email: '',

    // evento
    has_event: false,
    event_title: '',
    start_at: '',
    end_at: '',

    // comunicação
    communication_type: '',
    message: '',
})
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
            <RequestSteps v-model="step">
                <template #step-1>
                    <ApplicantSection :form="form"  />
                </template>

                <template #step-2>
                    <EventSection />
                </template>

                <template #step-3>
                    <CommunicationSection />
                </template>
            </RequestSteps>
        </div>
    </div>
</template>
