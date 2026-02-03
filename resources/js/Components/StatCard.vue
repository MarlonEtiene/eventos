<script setup lang="ts">
defineProps<{
    label: string
    value: number
}>()
</script>

<template>
    <div class="bg-white rounded-xl shadow px-6 py-4">
        <p class="text-sm text-slate-500">
            {{ label }}
        </p>

        <p class="text-3xl font-semibold text-slate-800 mt-1">
            {{ value }}
        </p>
    </div>
</template>
