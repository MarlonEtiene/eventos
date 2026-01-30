<script setup lang="ts">
import { ref, computed } from 'vue'

const props = defineProps<{
    modelValue: File[]
    maxFiles?: number
    maxSizeMb?: number
}>()

const emit = defineEmits(['update:modelValue'])

const maxFiles = props.maxFiles ?? 5
const maxSize = (props.maxSizeMb ?? 10) * 1024 * 1024

const error = ref<string>('')

function handleFiles(event: Event) {
    error.value = ''
    const input = event.target as HTMLInputElement
    if (!input.files) return

    const files = Array.from(input.files)

    if (files.length + props.modelValue.length > maxFiles) {
        error.value = `Máximo de ${maxFiles} arquivos.`
        emit('update:modelValue', [])
        input.value = ''
        return
    }

    for (const file of files) {
        if (file.size > maxSize) {
            error.value = `O arquivo "${file.name}" excede ${props.maxSizeMb ?? 10}MB.`
            emit('update:modelValue', [])
            input.value = ''
            return
        }
    }

    emit('update:modelValue', [...props.modelValue, ...files])
    input.value = ''
}


function remove(index: number) {
    const copy = [...props.modelValue]
    copy.splice(index, 1)
    emit('update:modelValue', copy)
}
</script>

<template>
    <div class="space-y-2">
        <input
            type="file"
            multiple
            accept="image/*,application/pdf"
            @change="handleFiles"
        />

        <p v-if="error" class="text-sm text-red-600">
            {{ error }}
        </p>

        <ul class="text-sm text-slate-600 space-y-1">
            <li
                v-for="(file, i) in modelValue"
                :key="i"
                class="flex justify-between items-center"
            >
                <span>{{ file.name }}</span>
                <button
                    type="button"
                    class="text-red-500 text-xs"
                    @click="remove(i)"
                >
                    Remover
                </button>
            </li>
        </ul>
    </div>
</template>
