<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue'
import StatCard from '@/components/StatCard.vue'
import { Link, router } from '@inertiajs/vue3'
import { formatters } from "@/composables/formatters";
import SortableTh from '@/components/SortableTh.vue'

const { formatDate, translateStatus } = formatters();

const props = defineProps<{
    stats: any
    requests: {
        data: any[]
        current_page: number
        last_page: number
        links: any[]
    }
    filters: {
        status?: string
        sort_by: string
        sort_dir: 'asc' | 'desc'
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

function typeIcon(item: any) {
    if (item.has_event && item.has_communication) {
        return {
            icons: ['🗓️', '📣'],
            label: 'Evento + Comunicação',
        }
    }

    if (item.has_event) {
        return {
            icons: ['🗓️'],
            label: 'Evento',
        }
    }

    return {
        icons: ['📣'],
        label: 'Comunicação',
    }
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

                    <SortableTh
                        label="Título"
                        column="title"
                        :active-column="filters.sort_by"
                        :direction="filters.sort_dir"
                        @sort="sortBy"
                    />

                    <SortableTh
                        label="Solicitante"
                        column="name"
                        :active-column="filters.sort_by"
                        :direction="filters.sort_dir"
                        @sort="sortBy"
                    />

                    <SortableTh
                        label="Status"
                        column="status"
                        :active-column="filters.sort_by"
                        :direction="filters.sort_dir"
                        @sort="sortBy"
                    />

                    <SortableTh
                        label="Data"
                        column="created_at"
                        :active-column="filters.sort_by"
                        :direction="filters.sort_dir"
                        @sort="sortBy"
                    />

                    <th class="px-6 py-3 text-right text-xs font-semibold">
                        Ações
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y">
                <tr
                    v-for="item in requests.data"
                    :key="item.id"
                    class="hover:bg-slate-50"
                >
                    <td class="px-6 py-4">
                        <div
                            class="flex items-center gap-1 text-lg"
                            :title="typeIcon(item).label"
                        >
                            <span v-for="(icon, i) in typeIcon(item).icons" :key="i">
                                {{ icon }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 font-medium">
                        {{ item.title ?? item.comm_type?.name}}
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

            <div
                v-if="requests.last_page > 1"
                class="flex justify-between items-center px-6 py-4 border-t text-sm"
            >
                <span class="text-slate-500">
                    Página {{ requests.current_page }} de {{ requests.last_page }}
                </span>

                <div class="flex gap-2">
                    <button
                        v-for="link in requests.links"
                        :key="link.label"
                        v-html="link.label"
                        :disabled="!link.url"
                        @click="link.url && router.get(link.url, {}, { preserveState: true })"
                        class="px-3 py-1 rounded border text-sm"
                        :class="[
                            link.active
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'bg-white hover:bg-slate-100', !link.url && 'opacity-40 cursor-not-allowed'
                                ]"
                    />
                </div>
            </div>
        </section>

    </AdminLayout>
</template>
