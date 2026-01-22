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
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <div>
<!--                <InputLabel for="title" value="Nome do Evento"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />-->
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
<!--                <InputLabel for="location" value="Local"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />-->
                <select v-model="form.location"
                        :disabled="readonly"
                        class="textInput w-full text-tiny"
                >
                    <option :value="null">Selecione o local</option>
                    <option v-for=" l in locals" :value="l">{{ l }}</option>
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
<!--                <InputLabel for="estimated_audience" value="Estimativa de participantes" required
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1"
                />-->
                <input v-model="form.estimated_audience"
                       class="textInput w-full text-tiny" placeholder="Estimativa de participantes"/>
            </div>
            <div></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <textarea v-model="form.objective" class="textInput w-full text-tiny h-20" placeholder="Objetivo *" />
            <textarea v-model="form.activities" class="textInput w-full text-tiny h-20" placeholder="Atividades previstas *" />
            <textarea v-model="form.resources" class="textInput w-full text-tiny h-20" placeholder="Recursos necessários *" />
            <textarea v-model="form.responsibles" class="textInput w-full text-tiny h-20" placeholder="Responsáveis pela execução *" />
        </div>
    </div>
</template>
