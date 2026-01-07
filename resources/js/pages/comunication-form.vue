<script setup lang="ts">
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue'
import InputError from '@/components/InputError.vue'

const previews = ref<string[]>([])

const form = useForm({
    requester_name: '',
    requester_department: '',
    whatsapp: '',
    contact_email: '',
    service_type: '',
    service_type_other: '',
    title: '',
    event_date: '',
    event_start_time: '',
    event_end_time: '',
    location: '',
    target_audience: '',
    content_text: '',
    delivery_date: '',
    additional_notes: '',
    attachments: [] as File[],
})

const serviceTypes = [
    'Cobertura de eventos (foto/vídeo)',
    'Criação artística (digital)',
    'Divulgação pré-evento',
    'Divulgação pós-evento',
    'Produção gráfica',
    'Resenha de texto',
    'Outros',
]

function handleFiles(e: Event) {
    const input = e.target as HTMLInputElement
    if (!input.files) return

    form.attachments = Array.from(input.files)

    previews.value = form.attachments.map(file =>
        file.type.startsWith('image/')
            ? URL.createObjectURL(file)
            : ''
    )
}

function submit() {
    form.post(route('communication-requests.store'), {
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Solicitação de Comunicação" />

    <div class="min-h-screen bg-slate-100 p-4">

        <InstitutionalHeader
            title="Solicitação de Comunicação"
            subtitle="Formulário Institucional"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-4 space-y-6 mt-2">

            <h1 class="text-xl font-bold text-center">
                Solicitação de Comunicação – HMAS
            </h1>

            <!-- 1. IDENTIFICAÇÃO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">
                    1. Identificação do Solicitante
                </h2>

                <input
                    v-model="form.requester_name"
                    class="input"
                    placeholder="Nome completo *"
                />
                <InputError :message="form.errors.requester_name" />

                <input
                    v-model="form.requester_department"
                    class="input"
                    placeholder="Setor / Equipe *"
                />
                <InputError :message="form.errors.requester_department" />
            </section>

            <!-- 2. CONTATO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">
                    2. Contato
                </h2>

                <input
                    v-model="form.whatsapp"
                    class="input"
                    placeholder="WhatsApp *"
                />
                <InputError :message="form.errors.whatsapp" />

                <input
                    v-model="form.contact_email"
                    type="email"
                    class="input"
                    placeholder="E-mail para contato *"
                />
                <InputError :message="form.errors.contact_email" />
            </section>

            <!-- 3. TIPO DE SERVIÇO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">
                    3. Tipo de Serviço Solicitado
                </h2>

                <select v-model="form.service_type" class="input">
                    <option value="">Selecione *</option>
                    <option
                        v-for="t in serviceTypes"
                        :key="t"
                        :value="t"
                    >
                        {{ t }}
                    </option>
                </select>

                <InputError :message="form.errors.service_type" />

                <input
                    v-if="form.service_type === 'Other'"
                    v-model="form.service_type_other"
                    class="input"
                    placeholder="Descreva o serviço"
                />
            </section>

            <!-- 4. EVENTO / CONTEXTO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">
                    4. Evento / Contexto
                </h2>

                <input
                    v-model="form.title"
                    class="input"
                    placeholder="Título da solicitação *"
                />

                <input
                    type="date"
                    v-model="form.event_date"
                    class="input"
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input
                        type="time"
                        v-model="form.event_start_time"
                        class="input"
                        placeholder="Hora inicial"
                    />
                    <input
                        type="time"
                        v-model="form.event_end_time"
                        class="input"
                        placeholder="Hora final"
                    />
                </div>

                <input
                    v-model="form.location"
                    class="input"
                    placeholder="Local do evento"
                />
            </section>

            <!-- 5. CONTEÚDO DA PEÇA -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">
                    5. Conteúdo da Peça
                </h2>

                <textarea
                    v-model="form.content_text"
                    class="input h-28"
                    placeholder="Texto, pessoas envolvidas, frases obrigatórias, projetos citados *"
                />
            </section>

            <!-- 6. PRAZO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">
                    6. Data de Entrega Desejada
                </h2>

                <input
                    type="date"
                    v-model="form.delivery_date"
                    class="input"
                />
            </section>

            <!-- 7. ANEXOS -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">
                    7. Referências / Anexos
                </h2>

                <input
                    type="file"
                    multiple
                    accept="image/*,application/pdf"
                    @change="handleFiles"
                />

                <div
                    v-if="previews.length"
                    class="grid grid-cols-3 gap-2 mt-2"
                >
                    <img
                        v-for="(img, i) in previews"
                        :key="i"
                        :src="img"
                        class="h-24 w-full object-cover rounded"
                    />
                </div>
            </section>

            <!-- 8. OBSERVAÇÕES -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">
                    8. Observações Finais
                </h2>

                <textarea
                    v-model="form.additional_notes"
                    class="input h-24"
                    placeholder="Informações adicionais"
                />
            </section>

            <!-- SUBMIT -->
            <button
                type="submit"
                :disabled="form.processing"
                @click.prevent="submit"
                class="w-full py-3 bg-blue-600 text-white rounded-lg font-semibold
                       hover:bg-blue-700 disabled:opacity-50 transition"
            >
                Enviar solicitação
            </button>

        </div>
    </div>
</template>

<style scoped>
.card {
    background: white;
    border-radius: 1rem;
    padding: 1rem;
    box-shadow: 0 4px 10px rgba(0,0,0,.05);
    space-y: 0.75rem;
}

.section-title {
    font-weight: 600;
    font-size: 0.9rem;
    color: #334155;
    margin-bottom: 0.5rem;
}

.input {
    width: 100%;
    border-radius: 0.75rem;
    padding: 0.65rem 0.75rem;
    border: 1px solid #d1d5db;
    font-size: 0.9rem;
}

.input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59,130,246,.4);
}

</style>
