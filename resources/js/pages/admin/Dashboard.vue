<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue'
import StatCard from '@/components/StatCard.vue'
import { Link, router } from '@inertiajs/vue3'
import {formatters} from "@/composables/formatters";

const { formatDate, translateStatus } = formatters();

const props = defineProps<{
    stats: any
    requests: any[]
    filters: {
        status?: string
        sort_by: string
        sort_dir: string
    }
}>()

function applyFilters(params: Record<string, any>) {
    router.get(route('admin.dashboard.index'), {
        ...props.filters,
        ...params,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

function toggleSort() {
    applyFilters({
        sort_dir: props.filters.sort_dir === 'asc' ? 'desc' : 'asc',
    })
}

function typeIcon(item: any) {
    if (item.has_event && item.has_communication) {
        return { icon: '🗓️📣', label: 'Evento + Comunicação' }
    }
    if (item.has_event) {
        return { icon: '🗓️', label: 'Evento' }
    }
    return { icon: '📣', label: 'Comunicação' }
}

function isSortedBy(column: string) {
    return props.filters.sort_by === column
}

function sortIcon(column: string) {
    if (!isSortedBy(column)) return ''
    return props.filters.sort_dir === 'asc' ? '↑' : '↓'
}

function sortBy(column: string) {
    const direction =
        props.filters.sort_by === column && props.filters.sort_dir === 'asc'
            ? 'desc'
            : 'asc'

    router.get(
        route('admin.dashboard.index'),
        {
            ...props.filters,
            sort_by: column,
            sort_dir: direction,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    )
}

</script>

<template>
    <AdminLayout>

        <!-- Cards -->
        <div class="grid grid-cols-4 gap-6 mb-10">
            <StatCard label="Novas" :value="stats.sended" />
            <StatCard label="Aprovadas" :value="stats.approved" />
            <StatCard label="Reprovadas" :value="stats.rejected" />
            <StatCard label="Total" :value="stats.total" />
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow mb-6 px-6 py-4 flex gap-4 items-center">
            <select
                class="border rounded-lg pl-3 pr-10 py-2 text-sm min-w-[150px]
                focus:outline-none focus:ring-2 focus:ring-blue-500"
                :value="filters.status ?? ''"
                @change="applyFilters({ status: $event.target.value || null })"
            >
                <option value="">Todos os status</option>
                <option value="sended">Enviadas</option>
                <option value="approved">Aprovadas</option>
                <option value="rejected">Reprovadas</option>
            </select>
        </div>

        <!-- Tabela -->
        <section class="bg-white rounded-xl shadow">
            <table class="min-w-full divide-y">
                <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold">
                        Tipo
                    </th>

                    <th
                        class="px-6 py-3 text-left text-xs font-semibold cursor-pointer select-none"
                        @click="sortBy('title')"
                    >
                        Título
                        <span class="ml-1">{{ sortIcon('title') }}</span>
                    </th>

                    <th
                        class="px-6 py-3 text-left text-xs font-semibold cursor-pointer select-none"
                        @click="sortBy('name')"
                    >
                        Solicitante
                        <span class="ml-1">{{ sortIcon('name') }}</span>
                    </th>

                    <th
                        class="px-6 py-3 text-left text-xs font-semibold cursor-pointer select-none"
                        @click="sortBy('status')"
                    >
                        Status
                        <span class="ml-1">{{ sortIcon('status') }}</span>
                    </th>

                    <th
                        class="px-6 py-3 text-left text-xs font-semibold cursor-pointer select-none"
                        @click="sortBy('created_at')"
                    >
                        Data
                        <span class="ml-1">{{ sortIcon('created_at') }}</span>
                    </th>

                    <th class="px-6 py-3 text-right text-xs font-semibold">
                        Ações
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y">
                <tr
                    v-for="item in requests"
                    :key="item.id"
                    class="hover:bg-slate-50"
                >
                    <td class="px-6 py-4 text-lg">
                            <span
                                :title="typeIcon(item).label"
                                class="cursor-help"
                            >
                                {{ typeIcon(item).icon }}
                            </span>
                    </td>

                    <td class="px-6 py-4 font-medium">
                        {{ item.title }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ item.name }} · {{ item.sector }}
                    </td>

                    <td class="px-6 py-4 text-sm">
                        {{ translateStatus(item.status) }}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ formatDate(item.created_at, null, 'DD/MM/YYYY HH:mm') }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        <Link
                            :href="route('admin.manager.show', item.id)"
                            class="text-blue-600 hover:underline text-sm"
                        >
                            Visualizar
                        </Link>
                    </td>
                </tr>
                </tbody>
            </table>
        </section>

    </AdminLayout>
</template>
