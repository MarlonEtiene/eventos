<script setup lang="ts">
import { watch } from 'vue'
import InputLabel from '@/components/InputLabel.vue'
import InputError from "@/components/InputError.vue";

const props = defineProps<{
    form: any
    readonly?: boolean
}>()

const emit = defineEmits<{
    (e: 'decide-event', value: boolean): void
}>()

watch(
    () => props.form.has_event,
    (value) => {
        if (value !== null && value !== undefined) {
            emit('decide-event', value)
        }
    }
)
</script>

<template>
    <div class="p-2 space-y-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <InputLabel for="name" value="Nome"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.name"
                    :disabled="readonly"
                    class="textInput w-full text-tiny"
                />
                <InputError :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="email" value="E-Mail"
                            class="text-sm overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.email"
                    :disabled="readonly"
                    class="textInput w-full text-tiny"
                />
                <InputError :message="form.errors.email" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <InputLabel for="function" value="Função"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.function"
                    :disabled="readonly"
                    class="textInput w-full text-tiny"
                />
                <InputError :message="form.errors.function" />
            </div>
            <div>
                <InputLabel for="sector" value="Setor"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.sector"
                    :disabled="readonly"
                    class="textInput w-full text-tiny"
                />
                <InputError :message="form.errors.sector" />
            </div>
            <div>
                <InputLabel for="phone" value="WhatsApp/Telefone"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    v-model="form.phone"
                    :disabled="readonly"
                    class="textInput w-full text-tiny"
                />
            </div>
            <InputError :message="form.errors.phone" />
        </div>
        <div class="mt-4 pt-4 border-t">
            <p class="text-sm font-semibold text-gray-700 mb-2">
                Deseja agendar um evento?
            </p>

            <div class="flex gap-4">
                <label
                    class="flex items-center gap-2 px-4 py-2 rounded-lg border cursor-pointer text-sm transition"
                    :class="form.has_event === true
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white border-gray-300 hover:bg-gray-50'"
                >
                    <input
                        type="radio"
                        class="hidden"
                        :disabled="readonly"
                        :value="true"
                        v-model="form.has_event"
                    />
                    Sim <fai v-if="form.has_event === true" icon="fa-solid fa-check-circle" class="text-green-600" />
                </label>

                <label
                    class="flex items-center gap-2 px-4 py-2 rounded-lg border cursor-pointer text-sm transition"
                    :class="form.has_event === false
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white border-gray-300 hover:bg-gray-50'"
                >
                    <input
                        type="radio"
                        class="hidden"
                        :disabled="readonly"
                        :value="false"
                        v-model="form.has_event"
                    />
                    Não <fai v-if="form.has_event === false" icon="fa-solid fa-square-xmark" class="text-red-600"/>
                </label>
            </div>
        </div>
    </div>
</template>
