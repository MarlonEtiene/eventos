<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue'

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import timeGridPlugin from '@fullcalendar/timegrid'

import { PartyPopper, Megaphone } from 'lucide-vue-next'

type Request = {
    id: number
    status: string
    created_at: string
}

defineProps<{
    requests: Request[]
}>()

/* -------------------------
 * Status
 * ------------------------- */
const statusMap: Record<string, { label: string; class: string }> = {
    submitted: { label: 'Enviado', class: 'bg-blue-100 text-blue-800' },
    approved_by_admin: { label: 'Em análise da Direção', class: 'bg-yellow-100 text-yellow-800' },
    approved: { label: 'Aprovado', class: 'bg-green-100 text-green-800' },
    rejected_by_admin: { label: 'Reprovado', class: 'bg-red-100 text-red-800' },
    rejected_by_direction: { label: 'Reprovado', class: 'bg-red-100 text-red-800' },
}

const getStatus = (status: string) =>
    statusMap[status] ?? { label: 'Desconhecido', class: 'bg-gray-100 text-gray-800' }

const extractTime = (dateTime?: string) =>
    dateTime ? dateTime.substring(11, 16) : ''

/* -------------------------
 * Responsividade
 * ------------------------- */
const isMobile = ref(false)
const showCalendar = ref(true)

onMounted(() => {
    isMobile.value = window.innerWidth < 768
    showCalendar.value = !isMobile.value
})

/* -------------------------
 * Calendar options
 * ------------------------- */
const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    locale: 'pt-br',
    timeZone: 'America/Sao_Paulo',

    initialView: isMobile.value ? 'timeGridDay' : 'dayGridMonth',

    height: isMobile.value ? 420 : 520,
    contentHeight: 'auto',
    aspectRatio: 1.35,
    dayMaxEvents: true,

    headerToolbar: isMobile.value
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

    allDayText: 'Dia inteiro',
    moreLinkText: 'mais',

    events: '/calendar/events',

    dateClick(info: any) {
        alert('Evento ou Comunicação?')
        router.visit(route('applicant.dashboard.create'), {
            data: { event_date: info.dateStr },
        })
    },

    eventClassNames(arg: any) {
        return arg.event.extendedProps.status === 'approved'
            ? ['fc-approved']
            : ['fc-pending']
    },

    eventDidMount(info: any) {
        info.el.title = `${extractTime(info.event.startStr)} - ${extractTime(info.event.endStr)}`
    },
}))
</script>

<template>
    <Head title="Solicitações de Evento" />

    <div class="min-h-screen bg-slate-100 flex flex-col">

        <InstitutionalHeader
            title="Solicitações de Evento"
            subtitle="Acompanhamento"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <div class="flex-1 px-3 md:px-4 py-4 space-y-6 max-w-5xl mx-auto w-full">

            <!-- CTA -->
            <div class="flex gap-2">
                <Link
                    :href="route('applicant.dashboard.new-event')"
                    class="flex items-center justify-center gap-2 w-full py-3 bg-blue-600 text-white
                           rounded-xl font-semibold hover:bg-blue-700 transition"
                >
                    <PartyPopper class="w-5 h-5" />
                    Solicitação de Evento
                </Link>

                <Link
                    :href="route('applicant.dashboard.new-communication')"
                    class="flex items-center justify-center gap-2 w-full py-3 bg-green-600 text-white
                           rounded-xl font-semibold hover:bg-green-700 transition"
                >
                    <Megaphone class="w-5 h-5" />
                    Solicitação de Comunicação
                </Link>
            </div>

            <!-- Toggle mobile -->
            <button
                v-if="isMobile"
                class="w-full py-2 bg-slate-200 rounded-lg text-sm"
                @click="showCalendar = !showCalendar"
            >
                {{ showCalendar ? 'Ocultar calendário' : 'Mostrar calendário' }}
            </button>

            <!-- Calendário -->
            <div
                v-if="showCalendar"
                class="bg-white rounded-xl shadow p-3"
            >
                <FullCalendar :options="calendarOptions" />
            </div>

            <!-- Lista -->
            <div class="space-y-3">
                <div
                    v-for="request in requests"
                    :key="request.id"
                    class="bg-white rounded-xl shadow p-4 flex justify-between"
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

.fc .fc-daygrid-day-frame {
    min-height: 80px;
}

.fc-event {
    border-radius: 6px;
    padding: 2px 4px;
    font-size: 0.75rem;
}

/* Mobile ajustes */
@media (max-width: 768px) {
    .fc {
        font-size: 0.8rem;
    }

    .fc-toolbar-title {
        font-size: 1rem !important;
        text-align: center;
        line-height: 1.2;
    }

    .fc-header-toolbar {
        flex-wrap: wrap;
        gap: 6px;
    }

    .fc-button {
        padding: 4px 8px !important;
        font-size: 0.75rem !important;
    }
}
</style>
