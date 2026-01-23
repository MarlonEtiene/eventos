<script setup lang="ts">

import InputLabel from '@/components/InputLabel.vue'
import {usePage} from "@inertiajs/vue3";

const props = defineProps<{
    form: any
    readonly?: boolean
}>()

const page = usePage()
const prefillStart = page.props.prefillStart as string | null
const prefillEnd = page.props.prefillEnd as string | null

const publicos = [
    'Alunos',
    'Pacientes',
    'Comunidade',
    'Profissionais',
    'Acompanhantes',
]

const locals = [
    'Auditório HMAS - 2º andar',
    'Sala de reuniões - 2 ºandar',
    'Sala de reuniões (Direção) - 2º andar',
    'Espaço de Convivência - 2º andar',
    'Brinquedoteca - 11º andar',
    'Parquinho - 11º andar',
    'Sala de Aula (Residência Médica) - 11º andar',
    'CER',
    '3º andar',
    '4º andar',
    '5º andar',
    '6º andar',
    '7º andar',
    '8º andar',
    '9º andar',
    '10º andar',
    '11º andar',
]

props.form.start_at = prefillStart
props.form.end_at = prefillEnd

</script>

<template>
    <div class="p-2 space-y-3">
        <h2 class="text-sm font-semibold text-slate-700">- Solicitação de Evento</h2>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <div>
                <input
                    v-model="form.name"
                    :disabled="readonly"
                    class="textInput w-full text-tiny"
                    placeholder="Nome do Evento"
                />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <InputLabel for="start_at" value="Data/Hora de Início"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input type="datetime-local" v-model="form.start_at" class="textInput w-full text-tiny" />
            </div>
            <div>
                <InputLabel for="end_at" value="Data/Hora de Fim"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input type="datetime-local" v-model="form.end_at" class="textInput w-full text-tiny" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <div>
                <select v-model="form.location"
                        :disabled="readonly"
                        class="textInput w-full text-tiny"
                >
                    <option :value="null" disabled>Selecione o local</option>
                    <option v-for="l in locals" :value="l">{{ l }}</option>
                </select>

            </div>
            <div>
                <InputLabel for="target_audience" value="Público-alvo" required
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1"
                />
                <div class="grid grid-cols-3 gap-2 mt-1">
                    <label v-for="p in publicos" :key="p" class="flex items-center gap-2 text-tiny">
                        <input type="checkbox" :value="p" v-model="form.target_audience" />
                        {{ p }}
                    </label>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <input v-model="form.others_audience"
                       class="textInput w-full text-tiny" placeholder="Publico-Alvo Outros"/>
            </div>
            <div>
                <input v-model="form.estimated_audience"
                       class="textInput w-full text-tiny" placeholder="Estimativa de participantes"/>
            </div>
            <div></div>
        </div>

        <h2 class="text-sm font-semibold text-slate-700">- Sobre o Evento</h2>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <textarea v-model="form.objective" class="textInput w-full text-tiny h-20" placeholder="Objetivo *" />
            <textarea v-model="form.activities" class="textInput w-full text-tiny h-20" placeholder="Atividades previstas *" />
            <textarea v-model="form.resources" class="textInput w-full text-tiny h-20" placeholder="Recursos necessários *" />
            <textarea v-model="form.responsibles" class="textInput w-full text-tiny h-20" placeholder="Responsáveis pela execução *" />
        </div>

        <h2 class="text-sm font-semibold text-slate-700">- Itens Especiais do Evento</h2>
        <div>
            <label class="text-sm font-bold">Haverá lanche/alimentação?</label>
            <select v-model="form.with_snack" class="textInput w-full text-tiny">
                <option :value="null" disabled>Selecione</option>
                <option :value="true">Sim</option>
                <option :value="false">Não</option>
            </select>
        </div>

        <textarea
            v-if="form.with_snack"
            v-model="form.snack_description"
            class="textInput w-full text-tiny h-20"
            placeholder="Qual lanche/alimentação?"
        />

        <div>
            <label class="text-sm font-bold">Haverá brinde ou premiação?</label>
            <select v-model="form.with_gift" class="textInput w-full text-tiny">
                <option :value="null" disabled>Selecione</option>
                <option :value="true">Sim</option>
                <option :value="false">Não</option>
            </select>
        </div>

        <textarea
            v-if="form.with_gift"
            v-model="form.gift_description"
            class="textInput w-full text-tiny h-20"
            placeholder="Quais brindes/premiações?"
        />

        <!-- CONTRIBUIÇÃO -->
        <div>
            <label class="text-sm font-bold">Solicitação de Recursos ou Notificação de Contribuição Externa/Doações</label>
            <select v-model="form.with_contribution" class="textInput w-full text-tiny">
                <option :value="null" disabled>Haverá solicitação de recursos ou contribuição externa?</option>
                <option :value="true">Sim</option>
                <option :value="false">Não</option>
            </select>
        </div>
        <textarea
            v-if="form.with_contribution"
            v-model="form.contribution_description"
            class="textInput w-full text-tiny h-20"
            placeholder="Descrever solicitação/contribuição"
        />
    </div>
</template>
