<script setup lang="ts">

import { ref } from "vue";
import RequestAttachments from "@/pages/components/RequestAttachments.vue";
import InputError from "@/components/InputError.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps<{
    form: any
    readonly?: boolean
}>()

const previews = ref<string[]>([])
const page = usePage()
const prefillDeliveryDate = page.props.prefillDeliveryDate as string | null

const serviceTypes = [
    'Cobertura de evento (foto/vídeo)',
    'Criação de arte (digital)',
    'Divulgação pré-evento',
    'Divulgação pós-evento',
    'Produção gráfica',
    'Resenha de texto',
    'Outros',
]

props.form.delivery_date = prefillDeliveryDate;

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
            <InputError :message="form.errors.communication_type_other" />

            <!-- Título -->
            <textarea
                v-model="form.art_image_text"
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
                <InputError :message="form.errors.attachments" />
            </div>

            <!-- Observações -->
            <textarea
                v-model="form.observations"
                class="textInput w-full text-tiny h-28"
                placeholder="Informações adicionais ou observações (opcional)"
            />
            <InputError :message="form.errors.observations" />

            <div>
<!--                <h2 class="font-semibold text-slate-700">
                    Declaração do Solicitante
                </h2>-->
                <label class="flex items-start gap-2 text-sm text-slate-700">
                    <input
                        type="checkbox"
                        v-model="form.declaration"
                        class="mt-1"
                    />
                    Estou ciente de que os pedidos à área de Comunicação devem ser feitos com antecedência mínima de 7 (sete) dias.
                </label>
            </div>
        </div>
    </div>
</template>
