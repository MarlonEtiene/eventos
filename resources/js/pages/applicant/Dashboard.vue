<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue'

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import timeGridPlugin from '@fullcalendar/timegrid'

type Request = {
    id: number
    status: string
    created_at: string
}

defineProps<{
    requests: Request[]
}>()

const statusMap: Record<string, { label: string; class: string }> = {
    submitted: {
        label: 'Enviado',
        class: 'bg-blue-100 text-blue-800',
    },
    approved_by_admin: {
        label: 'Em análise da Direção',
        class: 'bg-yellow-100 text-yellow-800',
    },
    approved: {
        label: 'Aprovado',
        class: 'bg-green-100 text-green-800',
    },
    rejected_by_admin: {
        label: 'Reprovado',
        class: 'bg-red-100 text-red-800',
    },
    rejected_by_direction: {
        label: 'Reprovado',
        class: 'bg-red-100 text-red-800',
    },
}

const getStatus = (status: string) =>
    statusMap[status] ?? {
        label: 'Desconhecido',
        class: 'bg-gray-100 text-gray-800',
    }

const extractTime = (dateTime?: string) => {
    if (!dateTime) return ''
    return dateTime.substring(11, 16) // HH:mm
}

const calendarOptions = {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    locale: 'pt-br',
    height: 'auto',
    timeZone: 'America/Sao_Paulo',

    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },

    events: '/calendar/events',

    dateClick(info) {
        router.visit(route('applicant.dashboard.create'), {
            data: { event_date: info.dateStr },
        })
    },

    eventClassNames(arg) {
        if (arg.event.extendedProps.status === 'approved') {
            return ['fc-approved']
        }

        return ['fc-pending']
    },
    eventDidMount(info) {
        info.el.title = `${extractTime(info.event.startStr)} - ${extractTime(info.event.endStr)}`
    },
    eventClick(info) {
        alert(
            `${info.event.title}\n` +
            `${extractTime(info.event.startStr)} - ${extractTime(info.event.endStr)}`
        )
    }
}
</script>

<template>
    <Head title="Solicitações de Evento" />

    <div class="min-h-screen bg-slate-100 flex flex-col">

        <!-- Header -->
        <InstitutionalHeader
            title="Solicitações de Evento"
            subtitle="Acompanhamento"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <!-- Conteúdo -->
        <div class="flex-1 px-4 py-4 space-y-6 max-w-3xl mx-auto w-full">

            <!-- CTA principal -->
            <Link
                :href="route('applicant.dashboard.create')"
                class="block w-full text-center py-3 bg-blue-600 text-white
                       rounded-xl font-semibold hover:bg-blue-700 transition"
            >
                Nova Solicitação
            </Link>

            <!-- Calendário -->
            <div class="bg-white rounded-xl shadow p-3">
                <FullCalendar :options="calendarOptions" />
            </div>

            <!-- Lista de solicitações -->
            <div class="space-y-3">
                <div
                    v-for="request in requests"
                    :key="request.id"
                    class="bg-white rounded-xl shadow p-4 flex
                           items-center justify-between"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-700">
                            Solicitação #{{ request.id }}
                        </p>
                        <p class="text-xs text-slate-400">
                            {{ new Date(request.created_at).toLocaleDateString() }}
                        </p>
                    </div>

                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold"
                        :class="getStatus(request.status).class"
                    >
                        {{ getStatus(request.status).label }}
                    </span>
                </div>

                <div
                    v-if="!requests.length"
                    class="bg-white rounded-xl shadow p-6 text-center text-slate-500"
                >
                    Você ainda não enviou nenhuma solicitação.
                </div>
            </div>

        </div>

        <!-- Rodapé -->
        <div class="text-center py-4 text-xs text-slate-500">
            e-Eventos
        </div>

    </div>
</template>

<style>
.fc-approved {
    background-color: #16a34a !important;
    border-color: #15803d !important;
    color: white;
}

.fc-pending {
    background-color: #facc15 !important;
    border-color: #eab308 !important;
    color: #78350f;
}
</style>
