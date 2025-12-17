<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
//import { computed, ref } from 'vue'
import InputError from '@/components/InputError.vue'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue';

const form = useForm({
    // 1. Identificação do solicitante
    email_inicial: '',
    nome: '',
    cargo: '',
    setor: '',
    telefone: '',
    email: '',

    // 2. Informações do evento
    titulo: '',
    data: '',
    horario: '',
    local: '',
    publico_alvo: [] as string[],
    publico_outro: '',
    estimativa_participantes: '',

    // 3. Descrição
    descricao: '',
    objetivo: '',
    atividades: '',
    recursos: '',
    responsaveis: '',

    // 4. Itens especiais (Perguntas 17 a 20)
    tem_lanche: null as null | boolean,         // Q17
    lanche_descricao: '',                       // Q18

    tem_brinde: null as null | boolean,         // Q19
    brinde_descricao: '',                       // Q20

    // 5. Recursos / contribuições externas (Perguntas 21 e 22)
    tem_contribuicao: null as null | boolean,   // Q21
    contribuicao_descricao: '',                 // Q22 (Perguntas 21 e 22)

    // 6. Declaração
    declaracao: false,
})

const publicos = [
    'Pacientes',
    'Acompanhantes',
    'Profissionais',
    'Alunos',
    'Comunidade'
]

const submit = () => {
    alert('Teste!')
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

                <input v-model="form.email_inicial" class="input" placeholder="E-mail *" />
                <InputError :message="form.errors.email_inicial" />

                <input v-model="form.nome" class="input" placeholder="Nome completo *" />
                <input v-model="form.cargo" class="input" placeholder="Cargo/Função *" />
                <input v-model="form.setor" class="input" placeholder="Setor *" />
                <input v-model="form.telefone" class="input" placeholder="Telefone *" />
                <input v-model="form.email" class="input" placeholder="E-mail *" />
            </section>

            <!-- EVENTO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">2. Informações Sobre o Evento</h2>

                <input v-model="form.titulo" class="input" placeholder="Título do evento *" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="date" v-model="form.data" class="input" />
                    <input type="time" v-model="form.horario" class="input" />
                </div>

                <input v-model="form.local" class="input" placeholder="Local do evento" />

                <div>
                    <label class="text-sm font-medium">Público-alvo *</label>
                    <div class="grid grid-cols-2 gap-2 mt-1">
                        <label v-for="p in publicos" :key="p" class="flex items-center gap-2 text-sm">
                            <input type="checkbox" :value="p" v-model="form.publico_alvo" />
                            {{ p }}
                        </label>
                    </div>
                    <input v-model="form.publico_outro" class="input mt-2" placeholder="Outro" />
                </div>

                <input v-model="form.estimativa_participantes" class="input" placeholder="Estimativa de participantes" />
            </section>

            <!-- DESCRIÇÃO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">3. Descrição do Evento</h2>
                <textarea v-model="form.descricao" class="input h-24" placeholder="Descrição do evento *" />
                <textarea v-model="form.objetivo" class="input h-20" placeholder="Objetivo *" />
                <textarea v-model="form.atividades" class="input h-20" placeholder="Atividades previstas *" />
                <textarea v-model="form.recursos" class="input h-20" placeholder="Recursos necessários *" />
                <textarea v-model="form.responsaveis" class="input h-20" placeholder="Responsáveis pela execução *" />
            </section>

            <!-- ITENS ESPECIAIS -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">4. Itens Especiais do Evento</h2>

                <div>
                    <label class="text-sm font-bold">Haverá lanche/alimentação?</label>
                    <select v-model="form.tem_lanche" class="input">
                        <option :value="null">Selecione</option>
                        <option :value="true">Sim</option>
                        <option :value="false">Não</option>
                    </select>
                </div>

                <textarea
                    v-if="form.tem_lanche"
                    v-model="form.lanche_descricao"
                    class="input h-20"
                    placeholder="Qual lanche/alimentação?"
                />

                <div>
                    <label class="text-sm font-bold">Haverá brinde ou premiação?</label>
                    <select v-model="form.tem_brinde" class="input">
                        <option :value="null">Selecione</option>
                        <option :value="true">Sim</option>
                        <option :value="false">Não</option>
                    </select>
                </div>

                <textarea
                    v-if="form.tem_brinde"
                    v-model="form.brinde_descricao"
                    class="input h-20"
                    placeholder="Quais brindes/premiações?"
                />
            </section>

            <!-- CONTRIBUIÇÃO -->
            <section class="space-y-3">
                <h2 class="font-semibold text-slate-700">5. Solicitação de Recursos ou Notificação de Contribuição Externa/Doações</h2>

                <select v-model="form.tem_contribuicao" class="input">
                    <option :value="null">Haverá solicitação de recursos ou contribuição externa?</option>
                    <option :value="true">Sim</option>
                    <option :value="false">Não</option>
                </select>

                <textarea
                    v-if="form.tem_contribuicao"
                    v-model="form.contribuicao_descricao"
                    class="input h-24"
                    placeholder="Descrever solicitação/contribuição"
                />
            </section>

            <!-- DECLARAÇÃO -->
            <section class="space-y-2">
                <h2 class="font-semibold text-slate-700">6. Declaração do Candidato</h2>
                <label class="flex items-start gap-2 text-sm">
                    <input type="checkbox" v-model="form.declaracao" />
                    Declaro que as informações prestadas são verdadeiras e que o evento só será realizado mediante autorização da Direção.
                </label>
            </section>

            <button
                class="w-full py-3 bg-green-600 text-white rounded-lg font-semibold disabled:opacity-50"
                :disabled="form.processing || !form.declaracao"
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
