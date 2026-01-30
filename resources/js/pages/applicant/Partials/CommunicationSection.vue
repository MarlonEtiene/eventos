<script setup lang="ts">

import {computed, ref} from "vue";
import RequestAttachments from "@/pages/components/RequestAttachments.vue";
import InputError from "@/components/InputError.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps<{
    form: any
    readonly?: boolean
}>()

const attachmentErrors = computed(() => {
    const errors = props.form.errors || {}

    return Object.entries(errors)
        .filter(([key]) => key.startsWith('attachments.'))
        .map(([key, message]) => {
            const index = Number(key.split('.')[1]) + 1
            return `Arquivo ${index}: ${message}`
        })
})

const previews = ref<string[]>([])
const page = usePage()
const serviceTypes = page.props.comm_types

function handleFiles(e: Event) {
    const input = e.target as HTMLInputElement
    if (!input.files) return

    props.form.attachments = Array.from(input.files)

    previews.value = form.attachments
        .filter(file => file.type.startsWith('image/'))
        .map(file => URL.createObjectURL(file))
}

</script>

<template>
    <div class="p-2 space-y-3">
        <h2 class="text-sm font-semibold text-slate-700">- Solicitação de Comunicação</h2>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <select
                v-model="form.communication_type_id"
                :disabled="readonly"
                class="textInput w-full text-tiny"
            >
                <option value="">Tipo de serviço solicitado *</option>
                <option v-for="l in serviceTypes" :value="l.id">{{ l.name }}</option>
            </select>
            <InputError :message="form.errors.communication_type_id" />

            <input
                v-if="form.communication_type === 'Outros'"
                v-model="form.communication_type_other"
                :readonly="readonly"
                class="textInput w-full text-tiny"
                placeholder="Descreva o tipo de serviço"
            />
            <InputError :message="form.errors.communication_type_other" />

            <!-- Título -->
            <textarea
                v-model="form.art_image_text"
                :disabled="readonly"
                class="textInput w-full text-tiny h-28"
                placeholder="Em caso de solicitação de artes, qual texto deve constar na imagem?"
            />
            <InputError :message="form.errors.art_image_text" />

            <!-- Início -->
            <div>
                <label class="text-sm text-slate-600">Data de entrega desejada *</label>
                <input
                    type="date"
                    v-model="form.delivery_date"
                    :disabled="readonly"
                    class="textInput w-full text-tiny"
                />
                <InputError :message="form.errors.delivery_date" />
            </div>

            <!-- Anexos -->
            <div>
                <span class="text-sm font-semibold text-slate-700">Referências ou exemplos de peças (opcional)</span>
                <RequestAttachments
                    v-model="form.attachments"
                    :max-files="5"
                    :max-size-mb="10"
                />
                <ul v-if="attachmentErrors.length" class="text-sm text-red-600 space-y-1 mt-1">
                    <li v-for="(msg, i) in attachmentErrors" :key="i">
                        {{ msg }}
                    </li>
                </ul>
            </div>

            <!-- Observações -->
            <textarea
                v-model="form.observations"
                class="textInput w-full text-tiny h-28"
                :readonly="readonly"
                placeholder="Informações adicionais ou observações (opcional)"
            />
            <InputError :message="form.errors.observations" />

            <div>
                <label class="flex items-start gap-2 text-sm text-slate-700">
                    <input
                        type="checkbox"
                        v-model="form.aware"
                        class="mt-1"
                        :disabled="readonly"
                    />
                    Estou ciente de que os pedidos à área de Comunicação devem ser feitos com antecedência mínima de 7 (sete) dias.
                </label>
                <InputError :message="form.errors.aware" />
            </div>
        </div>
    </div>
</template>
