<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue'

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

type Request = {
    id: number
    type: 'event' | 'communication'
    title: string
    status: string
    created_at: string
}

defineProps<{
    requests: Request[]
}>()

/* Tabs */
const activeTab = ref<'list' | 'calendar'>('list')

/* Modal de escolha */
const showChoiceModal = ref(false)
const selectedDate = ref<string | null>(null)

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
    timeZone: 'America/Sao_Paulo',
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

    dateClick(info) {
        selectedDate.value = info.dateStr
        showChoiceModal.value = true
    },
}


/* Redirecionamentos */
const goToEvent = () => {
    showChoiceModal.value = false
    router.visit(route('event-requests.create'), {
        data: { event_date: selectedDate.value },
    })
}

const goToCommunication = () => {
    showChoiceModal.value = false
    router.visit(route('communication-requests.create'), {
        data: { event_date: selectedDate.value },
    })
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
                        ? 'bg-blue-600 text-white'
                        : 'text-slate-600'"
                    @click="activeTab = 'calendar'"
                >
                    Calendário
                </button>
            </div>

            <!-- TAB: LISTA -->
            <div v-if="activeTab === 'list'" class="space-y-3">
                <div
                    v-for="r in requests"
                    :key="r.id"
                    class="bg-white rounded-xl shadow p-4 flex justify-between items-center"
                >
                    <div>
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded"
                            :class="r.type === 'event'
                                ? 'bg-indigo-100 text-indigo-800'
                                : 'bg-emerald-100 text-emerald-800'"
                        >
                            {{ r.type === 'event' ? 'Evento' : 'Comunicação' }}
                        </span>

                        <p class="mt-1 text-sm font-medium text-slate-700">
                            {{ r.title }}
                        </p>

                        <p class="text-xs text-slate-400">
                            {{ new Date(r.created_at).toLocaleDateString() }}
                        </p>
                    </div>

                    <div class="text-right space-y-1">
                        <span
                            class="text-xs px-2 py-1 rounded-full font-semibold"
                            :class="statusMap[r.status]"
                        >
                            {{ r.status }}
                        </span>

<!--                        <Link
                            :href="route('requests.show', r.id)"
                            class="block text-xs text-blue-600 hover:underline"
                        >
                            Visualizar
                        </Link>-->
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
                    O que deseja solicitar?
                </h3>

                <button
                    class="w-full py-2 bg-blue-600 text-white rounded-lg"
                    @click="goToEvent"
                >
                    Solicitação de Evento
                </button>

                <button
                    class="w-full py-2 bg-green-600 text-white rounded-lg"
                    @click="goToCommunication"
                >
                    Solicitação de Comunicação
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
}
</style>
