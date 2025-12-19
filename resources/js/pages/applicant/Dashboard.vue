<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue'

type Request = {
    id: number
    status: string
    created_at: string
}

const props = defineProps<{
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
        label: 'Reprovado (Admin)',
        class: 'bg-red-100 text-red-800',
    },
    rejected_by_direction: {
        label: 'Reprovado (Direção)',
        class: 'bg-red-100 text-red-800',
    },
}

const getStatus = (status: string) =>
    statusMap[status] ?? {
        label: 'Desconhecido',
        class: 'bg-gray-100 text-gray-800',
    }
</script>

<template>
    <Head title="Minhas Solicitações" />

    <div class="min-h-screen bg-slate-100 flex flex-col">

        <InstitutionalHeader
            title="Solicitações de Evento"
            subtitle="Acompanhamento"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <div class="flex-1 px-4 py-6 space-y-4 max-w-3xl mx-auto w-full">

            <!-- Botão nova solicitação  route('event-requests.create')-->

            <Link
                :href="route('applicant.dashboard.create')"
                class="block w-full text-center py-3 bg-blue-600 text-white
                       rounded-xl font-semibold hover:bg-blue-700 transition"
            >
                Nova Solicitação
            </Link>

            <!-- Lista -->
            <div
                v-if="requests.length"
                class="space-y-3"
            >
                <div
                    v-for="request in requests"
                    :key="request.id"
                    class="bg-white rounded-xl shadow p-4 flex
                           items-center justify-between"
                >
                    <div class="space-y-1">
                        <p class="text-sm text-slate-600">
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
            </div>

            <!-- Empty state -->
            <div
                v-else
                class="bg-white rounded-xl shadow p-6 text-center
                       text-slate-500"
            >
                Você ainda não enviou nenhuma solicitação.
            </div>

        </div>

        <div class="text-center py-4 text-xs text-slate-500">
            e-Eventos
        </div>
    </div>
</template>
