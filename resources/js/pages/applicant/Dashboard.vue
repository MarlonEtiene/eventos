<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue'
import {formatters} from "@/composables/formatters";

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

const { formatDate } = formatters();

type Request = {
    id: number
    name: string
    email: string
    function: string
    sector: string
    type: 'event' | 'communication'
    title: string
    has_event: boolean
    status: string
    created_at: string
    date: any
}

defineProps<{
    requests: Request[]
}>()

/* Tabs */
const activeTab = ref<'list' | 'calendar'>('list')

/* Modal de escolha */
const showChoiceModal = ref(false)
const selectedDate = ref<string | null>(null)
const selectedStartTime = ref('09:00') // default amigável

/* Status visual */
const statusMap: Record<string, string> = {
    submitted: 'bg-blue-100 text-blue-800',
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
    pending_directorship: 'bg-yellow-100 text-yellow-800',
}

const isMobile = window.matchMedia('(max-width: 640px)').matches

/* Calendário */
const calendarOptions = {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    locale: 'pt-br',
    height: 'auto',

    events: '/calendar/events',

    headerToolbar: isMobile
        ? {
            left: 'prev,next',
            center: 'title',
            right: '',
        }
        : {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },

    buttonText: {
        today: 'Hoje',
        month: 'Mês',
        week: 'Semana',
        day: 'Dia',
    },
    dateClick(info: any) {
        selectedDate.value = info.dateStr
        showChoiceModal.value = true
    }
}

function eventContent(arg: any) {
    const view = arg.view.type
    const start = arg.start

    const time = start
        ? start.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit',
        })
        : ''

    const container = document.createElement('div')
    container.className = 'fc-event-clean'

    if (view === 'dayGridMonth') {
        // MONTH (mobile-first)
        const timeEl = document.createElement('div')
        timeEl.className = 'fc-event-time'
        timeEl.innerText = time

        const titleEl = document.createElement('div')
        titleEl.className = 'fc-event-title'
        titleEl.innerText = arg.title

        container.appendChild(timeEl)
        container.appendChild(titleEl)
    } else {
        container.innerText = `${time} – ${arg.title}`
    }

    return { domNodes: [container] }
}


/* Redirecionamentos */
const goToEvent = () => {
    showChoiceModal.value = false
    router.visit(route('applicant.request.create', {
        date: selectedDate.value,
        start: selectedStartTime.value,
    }))

}
</script>

<template>
    <Head title="Dashboard do Solicitante" />

    <div class="min-h-screen bg-slate-100 flex flex-col">

        <InstitutionalHeader
            title="Solicitações"
            subtitle="Acompanhamento"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <div class="flex-1 px-4 py-4 max-w-3xl mx-auto w-full space-y-4">

            <!-- Tabs -->
            <div class="flex bg-white rounded-xl shadow overflow-hidden">
                <button
                    class="flex-1 py-3 text-sm font-semibold"
                    :class="activeTab === 'list'
                        ? 'bg-blue-600 text-white'
                        : 'text-slate-600'"
                    @click="activeTab = 'list'"
                >
                    Minhas Solicitações
                </button>
                <button
                    class="flex-1 py-3 text-sm font-semibold"
                    :class="activeTab === 'calendar'
                        ? 'bg-green-600 text-white'
                        : 'text-slate-600'"
                    @click="activeTab = 'calendar'"
                >
                    Nova Solicitação
                </button>
            </div>

            <!-- TAB: LISTA -->
            <div v-if="activeTab === 'list'" class="space-y-3">
                <div
                    v-for="r in requests"
                    :key="r.id"
                    class="bg-white rounded-xl shadow p-4 flex justify-between items-start"
                >
                    <div class="space-y-1">
                        <!-- Tipo -->
                        <span
                            class="inline-block text-xs font-semibold px-2 py-1 rounded"
                            :class="r.type === 'event' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800'"
                        >
                            {{ r.type === 'event' ? 'Evento' : 'Comunicação' }}
                        </span>

                        <!-- Título -->
                        <p class="text-sm font-medium text-slate-800 leading-snug">
                            {{ r.title }}
                        </p>

                        <!-- Solicitante -->
                        <p class="text-xs text-slate-500">
                            {{ r.name }}
                            <span v-if="r.function || r.sector">
                                • {{ r.function }}
                                <span v-if="r.sector">
                                    — {{ r.sector }}
                                </span>
                            </span>
                        </p>

                        <!-- Data -->
                        <p class="text-xs text-slate-400">
                            {{ r.has_event  ? formatDate(r.date, null, 'DD/MM/YYYY HH:mm') : formatDate(r.date, null, 'DD/MM/YYYY') }}
                        </p>
                    </div>

                    <!-- Status / ação -->
                    <div class="text-right space-y-1">
                        <span
                            class="inline-block text-xs px-2 py-1 rounded-full font-semibold"
                            :class="statusMap[r.status]"
                        >
                            {{ r.status }}
                        </span>

                        <Link
                            :href="route('applicant.request.show', r.id)"
                            class="block text-xs text-blue-600 hover:underline"
                        >
                            Visualizar
                        </Link>
                    </div>
                </div>

                <div
                    v-if="!requests.length"
                    class="bg-white rounded-xl shadow p-6 text-center text-slate-500"
                >
                    Você ainda não enviou nenhuma solicitação.
                </div>
            </div>

            <!-- TAB: CALENDÁRIO -->
            <div v-if="activeTab === 'calendar'" class="bg-white rounded-xl shadow p-3">
                <FullCalendar :options="calendarOptions" />
            </div>
        </div>

        <!-- MODAL -->
        <div
            v-if="showChoiceModal"
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-xl p-6 w-80 space-y-4">
                <h3 class="text-center font-semibold text-slate-700">
                    Agendamento do Evento
                </h3>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-600">
                        Horário de início
                    </label>
                    <input
                        type="time"
                        v-model="selectedStartTime"
                        class="input"
                    />
                </div>

                <button
                    class="w-full py-2 bg-blue-600 text-white rounded-lg"
                    @click="goToEvent"
                >
                    Iniciar Solicitação
                </button>

                <button
                    class="w-full py-2 bg-gray-400 text-sm text-white rounded-lg"
                    @click="showChoiceModal = false"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</template>

<style>
/* Mobile: reduzir header do FullCalendar */
@media (max-width: 640px) {
    .fc-toolbar-title {
        font-size: 1rem;
        text-align: center;
    }

    .fc-toolbar.fc-header-toolbar {
        margin-bottom: 0.5rem;
    }

    .fc-button {
        padding: 0.35rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Base do evento */
    .fc-event-clean {
        font-size: 0.7rem;
        line-height: 1.1;
        padding: 2px 3px;
        overflow: hidden;
    }

    /* Hora (mais discreta) */
    .fc-event-time {
        font-size: 0.65rem;
        color: #475569; /* slate-600 */
    }

    /* Título */
    .fc-event-title {
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Melhor ajuste no mobile */
    @media (max-width: 640px) {
        .fc-event-clean {
            font-size: 0.65rem;
        }

        .fc-event-title {
            max-width: 100%;
        }
    }
}
</style>
