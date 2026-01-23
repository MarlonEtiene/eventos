<script setup lang="ts">

import {ref} from "vue";
import RequestAttachments from "@/pages/components/RequestAttachments.vue";
import InputError from "@/components/InputError.vue";

const props = defineProps<{
    form: any
    readonly?: boolean
}>()

const previews = ref<string[]>([])

const serviceTypes = [
    'Cobertura de evento (foto/vídeo)',
    'Criação de arte (digital)',
    'Divulgação pré-evento',
    'Divulgação pós-evento',
    'Produção gráfica',
    'Resenha de texto',
    'Outros',
]

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
        <h2 class="text-sm font-semibold text-slate-700">- Solicitação de Evento</h2>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <select v-model="form.communication_type" class="textInput w-full text-tiny">
                <option value="">Tipo de serviço solicitado *</option>
                <option v-for="t in serviceTypes" :key="t" :value="t">
                    {{ t }}
                </option>
            </select>
            <InputError :message="form.errors.communication_type" />

            <input
                v-if="form.communication_type === 'Outros'"
                v-model="form.communication_type_other"
                class="textInput w-full text-tiny"
                placeholder="Descreva o tipo de serviço"
            />

            <!-- Título -->
            <input v-model="form.title" class="textInput w-full text-tiny" placeholder="Título da solicitação *" />
            <InputError :message="form.errors.title" />

            <!-- Início -->
            <label class="text-sm text-slate-600">Início do evento / ação *</label>
            <input
                type="datetime-local"
                v-model="form.start_at"
                class="textInput w-full text-tiny"
            />
            <InputError :message="form.errors.start_at" />

            <!-- Fim -->
            <label class="text-sm text-slate-600">Término do evento / ação *</label>
            <input
                type="datetime-local"
                v-model="form.end_at"
                class="textInput w-full text-tiny"
            />
            <InputError :message="form.errors.end_at" />

            <!-- Público-alvo -->
            <input
                v-model="form.target_audience"
                class="textInput w-full text-tiny"
                placeholder="Público-alvo *"
            />
            <InputError :message="form.errors.target_audience" />

            <!-- Texto da peça -->
            <textarea
                v-model="form.message"
                class="textInput w-full text-tiny h-28"
                placeholder="Texto que deve constar na peça *"
            />
            <InputError :message="form.errors.message" />

            <!-- Data de entrega -->
            <input
                type="date"
                v-model="form.delivery_date"
                class="textInput w-full text-tiny"
            />
            <InputError :message="form.errors.delivery_date" />

            <!-- Anexos -->
            <RequestAttachments
                v-model="form.attachments"
                :max-files="5"
                :max-size-mb="10"
            />

            <!-- Observações -->
            <textarea
                v-model="form.observations"
                class="textInput w-full text-tiny h-28"
                placeholder="Informações adicionais ou observações (opcional)"
            />
        </div>
    </div>
</template>
