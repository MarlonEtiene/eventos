<script setup lang="ts">
defineProps<{
    label: string
    value: number
    active?: boolean
}>()

const emit = defineEmits<{
    (e: 'click'): void
}>()

function handleClick() {
    emit('click')
}
</script>

<template>
    <div
        class="rounded-xl shadow px-6 py-4 cursor-pointer transition hover:shadow-md hover:scale-[1.02]"
        @click="handleClick"
        :class="[
            active
                ? 'bg-blue-600 text-white shadow-lg scale-[1.02]'
                : 'bg-white text-slate-800 shadow hover:shadow-md hover:scale-[1.02]'
            ]"
    >
        <p class="text-sm"
           :class="active ? 'text-blue-100' : 'text-slate-500'" >
            {{ label }}
        </p>

        <p class="text-3xl font-semibold text-slate-800 mt-1">
            {{ value }}
        </p>
    </div>
</template>
