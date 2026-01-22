<script setup lang="ts">

import InputLabel from '@/components/InputLabel.vue';
import InputError from '@/components/InputError.vue';

defineProps<{
    form: any
    readonly?: boolean
}>()
</script>

<template>
    <div class="p-2 bg-gray-50 rounded-md border border-gray-200 space-y-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <InputLabel for="admission_at" value="Nome"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.name"
                    :disabled="readonly"
                    class="textInput w-full"
                />
            </div>
            <div>
                <InputLabel for="admission_at" value="E-Mail"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.sector"
                    :disabled="readonly"
                    class="textInput w-full"
                />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <InputLabel for="admission_at" value="Nome"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.name"
                    :disabled="readonly"
                    class="textInput w-full"
                />
            </div>
            <div>
                <InputLabel for="admission_at" value="E-Mail"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.sector"
                    :disabled="readonly"
                    class="textInput w-full"
                />
            </div>
            <div>
                <InputLabel for="admission_at" value="E-Mail"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.sector"
                    :disabled="readonly"
                    class="textInput w-full"
                />
            </div>
        </div>
    </div>
</template>
