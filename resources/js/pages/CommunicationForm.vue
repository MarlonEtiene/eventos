<script setup lang="ts">
import { ref } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue'
import InputError from '@/components/InputError.vue'
import RequestAttachments from "@/pages/components/RequestAttachments.vue";

const page = usePage()
const previews = ref<string[]>([])

const prefillStart = page.props.prefillStart as string | null
const prefillEnd = page.props.prefillEnd as string | null

const form = useForm({
    name: '',
    sector: '',
    phone: '',
    email: '',

    communication_type: '',
    communication_type_other: '',

    title: '',

    start_at: '',
    end_at: '',

    target_audience: '',
    message: '',

    delivery_date: '',
    observations: '',

    declaration: false,

    attachments: [] as File[],
})

if (prefillStart) {
    form.start_at = prefillStart
}

if (prefillEnd) {
    form.end_at = prefillEnd
}

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

    form.attachments = Array.from(input.files)

    previews.value = form.attachments
        .filter(file => file.type.startsWith('image/'))
        .map(file => URL.createObjectURL(file))
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

        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-4 space-y-4 mt-2">

            <h1 class="text-xl font-bold text-center">
                Solicitação de Comunicação
            </h1>

            <!-- Nome -->
            <input v-model="form.name" class="input" placeholder="Nome e sobrenome *" />
            <InputError :message="form.errors.name" />

            <!-- Setor -->
            <input v-model="form.sector" class="input" placeholder="Setor ou equipe solicitante *" />
            <InputError :message="form.errors.sector" />

            <!-- WhatsApp -->
            <input v-model="form.phone" class="input" placeholder="WhatsApp *" />
            <InputError :message="form.errors.phone" />

            <!-- Email -->
            <input v-model="form.email" type="email" class="input" placeholder="E-mail para contato *" />
            <InputError :message="form.errors.email" />

            <!-- Tipo de serviço -->
            <select v-model="form.communication_type" class="input">
                <option value="">Tipo de serviço solicitado *</option>
                <option v-for="t in serviceTypes" :key="t" :value="t">
                    {{ t }}
                </option>
            </select>
            <InputError :message="form.errors.communication_type" />

            <input
                v-if="form.communication_type === 'Outros'"
                v-model="form.communication_type_other"
                class="input"
                placeholder="Descreva o tipo de serviço"
            />

            <!-- Título -->
            <input v-model="form.title" class="input" placeholder="Título da solicitação *" />
            <InputError :message="form.errors.title" />

            <!-- Início -->
            <label class="text-sm text-slate-600">Início do evento / ação *</label>
            <input
                type="datetime-local"
                v-model="form.start_at"
                class="input"
            />
            <InputError :message="form.errors.start_at" />

            <!-- Fim -->
            <label class="text-sm text-slate-600">Término do evento / ação *</label>
            <input
                type="datetime-local"
                v-model="form.end_at"
                class="input"
            />
            <InputError :message="form.errors.end_at" />

            <!-- Público-alvo -->
            <input
                v-model="form.target_audience"
                class="input"
                placeholder="Público-alvo *"
            />
            <InputError :message="form.errors.target_audience" />

            <!-- Texto da peça -->
            <textarea
                v-model="form.message"
                class="input h-28"
                placeholder="Texto que deve constar na peça *"
            />
            <InputError :message="form.errors.message" />

            <!-- Data de entrega -->
            <input
                type="date"
                v-model="form.delivery_date"
                class="input"
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
                class="input h-24"
                placeholder="Informações adicionais ou observações (opcional)"
            />

            <!-- DECLARAÇÃO -->
            <section class="space-y-2">
                <h2 class="font-semibold text-slate-700">6. Declaração do Candidato</h2>
                <label class="flex items-start gap-2 text-sm">
                    <input type="checkbox" v-model="form.declaration" />
                    Declaro que as informações prestadas são verdadeiras e que o evento só será realizado mediante autorização da Direção.
                </label>
            </section>

            <button
                class="w-full py-3 bg-blue-600 text-white rounded-lg font-semibold disabled:opacity-50"
                :disabled="form.processing || !form.declaration"
                @click="submit"
            >
                Enviar solicitação
            </button>

        </div>
    </div>
</template>

<style scoped>
.input {
    width: 100%;
    border-radius: 0.75rem;
    padding: 0.7rem 0.75rem;
    border: 1px solid #d1d5db;
    font-size: 0.9rem;
}

.input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59,130,246,.4);
}
</style>
