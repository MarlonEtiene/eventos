<script setup lang="ts">
import { useForm, router, usePage } from '@inertiajs/vue3';
//import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import InstitutionalHeader from '@/Pages/Partials/InstitutionalHeader.vue';

const page = usePage()
const prefillStart = page.props.prefillStart as string | null
const prefillEnd = page.props.prefillEnd as string | null

const form = useForm({
    // 1. Identificação do solicitante
    email: '',
    name: '',
    function: '',
    sector: '',
    phone: '',

    // 2. Informações do evento
    title: '',
    start_at: '',
    end_at: '',
    location: '',
    target_audience: [] as string[],
    others_audience: '',
    estimated_audience: '',

    // 3. Descrição
    description: '',
    objective: '',
    activities: '',
    resources: '',
    responsibles: '',

    // 4. Itens especiais (Perguntas 17 a 20)
    with_snack: null as null | boolean,             // Q17
    snack_description: '',                          // Q18
    with_gift: null as null | boolean,              // Q19
    gift_description: '',                           // Q20

    // 5. resources / contribuições externas (Perguntas 21 e 22)
    with_contribution: null as null | boolean,      // Q21
    contribution_description: '',                   // Q22 (Perguntas 21 e 22)

    // 6. Declaração
    declaration: false,
})

if (prefillStart) {
    form.start_at = prefillStart
}

if (prefillEnd) {
    form.end_at = prefillEnd
}

const publicos = [
    'Pacientes',
    'Acompanhantes',
    'Profissionais',
    'Alunos',
    'Comunidade'
]

const submit = () => {
    form.post(route('event-requests.store'));
}
</script>

<template>
    <div class="min-h-screen bg-slate-100 p-4">
        <InstitutionalHeader
            title="Solicitação de Eventos"
            subtitle="Formulário Institucional"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-4 space-y-6 mt-2">

            <h1 class="text-xl font-bold text-center">Solicitação de Eventos – HMAS</h1>

            <!-- IDENTIFICAÇÃO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">1. Identificação do Solicitante</h2>

                <input v-model="form.email" class="input" placeholder="E-mail *" />
                <InputError :message="form.errors.email" />

                <input v-model="form.name" class="input" placeholder="name completo *" />
                <input v-model="form.function" class="input" placeholder="Função *" />
                <input v-model="form.sector" class="input" placeholder="Setor *" />
                <input v-model="form.phone" class="input" placeholder="Telefone *" />
            </section>

            <!-- EVENTO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">2. Informações Sobre o Evento</h2>

                <input v-model="form.title" class="input" placeholder="Título do evento *" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="datetime-local" v-model="form.start_at" class="input" />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="datetime-local" v-model="form.end_at" class="input" />
                </div>

                <input v-model="form.location" class="input" placeholder="Local do evento" />

                <div>
                    <label class="text-sm font-medium">Público-alvo *</label>
                    <div class="grid grid-cols-2 gap-2 mt-1">
                        <label v-for="p in publicos" :key="p" class="flex items-center gap-2 text-sm">
                            <input type="checkbox" :value="p" v-model="form.target_audience" />
                            {{ p }}
                        </label>
                    </div>
                    <input v-model="form.others_audience" class="input mt-2" placeholder="Outro" />
                </div>

                <input v-model="form.estimated_audience" class="input" placeholder="Estimativa de participantes" />
            </section>

            <!-- DESCRIÇÃO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">3. Descrição do Evento</h2>
                <textarea v-model="form.description" class="input h-24" placeholder="Descrição do evento *" />
                <textarea v-model="form.objective" class="input h-20" placeholder="Objetivo *" />
                <textarea v-model="form.activities" class="input h-20" placeholder="Atividades previstas *" />
                <textarea v-model="form.resources" class="input h-20" placeholder="Recursos necessários *" />
                <textarea v-model="form.responsibles" class="input h-20" placeholder="Responsáveis pela execução *" />
            </section>

            <!-- ITENS ESPECIAIS -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">4. Itens Especiais do Evento</h2>

                <div>
                    <label class="text-sm font-bold">Haverá lanche/alimentação?</label>
                    <select v-model="form.with_snack" class="input">
                        <option :value="null">Selecione</option>
                        <option :value="true">Sim</option>
                        <option :value="false">Não</option>
                    </select>
                </div>

                <textarea
                    v-if="form.with_snack"
                    v-model="form.snack_description"
                    class="input h-20"
                    placeholder="Qual lanche/alimentação?"
                />

                <div>
                    <label class="text-sm font-bold">Haverá brinde ou premiação?</label>
                    <select v-model="form.with_gift" class="input">
                        <option :value="null">Selecione</option>
                        <option :value="true">Sim</option>
                        <option :value="false">Não</option>
                    </select>
                </div>

                <textarea
                    v-if="form.with_gift"
                    v-model="form.gift_description"
                    class="input h-20"
                    placeholder="Quais brindes/premiações?"
                />
            </section>

            <!-- CONTRIBUIÇÃO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">5. Solicitação de Recursos ou Notificação de Contribuição Externa/Doações</h2>

                <select v-model="form.with_contribution" class="input">
                    <option :value="null" disabled>Haverá solicitação de recursos ou contribuição externa?</option>
                    <option :value="true">Sim</option>
                    <option :value="false">Não</option>
                </select>

                <textarea
                    v-if="form.with_contribution"
                    v-model="form.contribution_description"
                    class="input h-24"
                    placeholder="Descrever solicitação/contribuição"
                />
            </section>

            <!-- DECLARAÇÃO -->
            <section class="space-y-2">
                <h2 class="font-semibold text-slate-700">6. Declaração do Candidato</h2>
                <label class="flex items-start gap-2 text-sm">
                    <input type="checkbox" v-model="form.declaration" />
                    Declaro que as informações prestadas são verdadeiras e que o evento só será realizado mediante autorização da Direção.
                </label>
            </section>

            <button
                class="w-full py-3 bg-green-600 text-white rounded-lg font-semibold disabled:opacity-50"
                :disabled="form.processing || !form.declaration"
                @click="submit"
            >
                Enviar solicitação
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Tailwind v4 compatible: sem @apply com cores */
.input {
    width: 100%;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--tw-border-color, #d1d5db);
}

.input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); /* blue-500 */
}
</style>
