<script setup lang="ts">
const props = defineProps<{
    label: string
    column: string
    activeColumn: string
    direction: 'asc' | 'desc'
}>()

const emit = defineEmits<{
    (e: 'sort', column: string): void
}>()

function icon() {
    if (props.activeColumn !== props.column) return '↕'
    return props.direction === 'asc' ? '↑' : '↓'
}
</script>

<template>
    <th
        class="px-6 py-3 text-left text-xs font-semibold select-none
               cursor-pointer hover:text-blue-600 transition"
        @click="emit('sort', column)"
    >
        <div class="flex items-center gap-1 whitespace-nowrap group">
            <span>{{ label }}</span>
            <span
                class="text-[10px] opacity-0 group-hover:opacity-100 transition"
                :class="{ 'opacity-100': activeColumn === column }"
            >
                {{ icon() }}
            </span>
        </div>
    </th>
</template>
