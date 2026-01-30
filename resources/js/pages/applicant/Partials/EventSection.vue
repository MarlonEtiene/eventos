<script setup lang="ts">

import { usePage } from "@inertiajs/vue3";
import InputLabel from '@/components/InputLabel.vue'
import InputError from "@/components/InputError.vue";

const props = defineProps<{
    form: any
    readonly?: boolean
}>()

const page = usePage()
const locals = page.props.locals
const audiences = page.props.audiences

</script>

<template>
    <div class="p-2 space-y-3">
        <h2 class="text-sm font-semibold text-slate-700">- Solicitação de Evento</h2>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <div>
                <input
                    v-model="form.title"
                    :disabled="readonly"
                    class="textInput w-full text-tiny"
                    placeholder="Nome do Evento"
                />
                <InputError :message="form.errors.title" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <InputLabel for="start_at" value="Data/Hora de Início"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    type="datetime-local"
                    v-model="form.start_at"
                    :readonly="readonly"
                    class="textInput w-full text-tiny" />
                <InputError :message="form.errors.start_at" />
            </div>
            <div>
                <InputLabel for="end_at" value="Data/Hora de Fim"
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1" />
                <input
                    type="datetime-local"
                    v-model="form.end_at"
                    :readonly="readonly"
                    class="textInput w-full text-tiny" />
                <InputError :message="form.errors.end_at" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <div>
                <select v-model="form.local_id"
                        :disabled="readonly"
                        class="textInput w-full text-tiny"
                >
                    <option :value="null" disabled>Selecione o local</option>
                    <option v-for="l in locals" :value="l.id">
                        {{ l.name }}
                    </option>
                </select>
                <InputError :message="form.errors.local_id" />
            </div>
            <div>
                <InputLabel for="target_audience" value="Público-alvo" required
                            class="text-tiny overflow-hidden text-ellipsis whitespace-nowra mt-1"
                />
                <div class="grid grid-cols-3 gap-2 mt-1">
                    <label v-for="p in audiences" :key="p.id" class="flex items-center gap-2 text-tiny">
                        <input
                            type="checkbox"
                            :value="p.id"
                            :disabled="readonly"
                            v-model="form.target_audience" />
                        {{ p.name }}
                    </label>
                </div>
                <InputError :message="form.errors.target_audience" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <input
                    type="text"
                    v-model="form.others_audience"
                    :readonly="readonly"
                    class="textInput w-full text-tiny"
                    placeholder="Publico-Alvo Outros"/>
                <InputError :message="form.errors.others_audience" />
            </div>
            <div>
                <input
                    type="text"
                    v-model="form.estimated_audience"
                    :readonly="readonly"
                    class="textInput w-full text-tiny"
                    placeholder="Estimativa de participantes"/>
                <InputError :message="form.errors.estimated_audience" />
            </div>
            <div></div>
        </div>

        <h2 class="text-sm font-semibold text-slate-700">- Sobre o Evento</h2>
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-sm">
            <div>
                <textarea
                    v-model="form.objective"
                    class="textInput w-full text-tiny h-20"
                    :disabled="readonly"
                    placeholder="Objetivo *" />
                <InputError :message="form.errors.objective" />
            </div>
            <div>
                <textarea
                    v-model="form.activities"
                    class="textInput w-full text-tiny h-20"
                    :readonly="readonly"
                    placeholder="Atividades previstas *" />
                <InputError :message="form.errors.activities" />
            </div>
            <div>
                <textarea
                    v-model="form.resources"
                    class="textInput w-full text-tiny h-20"
                    :readonly="readonly"
                    placeholder="Recursos necessários *" />
                <InputError :message="form.errors.resources" />
            </div>
            <div>
                <textarea
                    v-model="form.responsibles"
                    class="textInput w-full text-tiny h-20"
                    :readonly="readonly"
                    placeholder="Responsáveis pela execução *" />
                <InputError :message="form.errors.responsibles" />
            </div>
        </div>

        <h2 class="text-base font-semibold text-slate-700">- Itens Especiais do Evento</h2>
        <div>
            <label class="text-sm font-bold">Haverá lanche/alimentação?</label>
            <select
                v-model="form.with_snack"
                :disabled="readonly"
                class="textInput w-full text-tiny"
            >
                <option :value="null" disabled>Selecione</option>
                <option :value="true">Sim</option>
                <option :value="false">Não</option>
            </select>
            <InputError :message="form.errors.with_snack" />
        </div>

        <div>
            <textarea
                v-if="form.with_snack"
                v-model="form.snack_description"
                :disabled="readonly"
                class="textInput w-full text-tiny h-20"
                placeholder="Qual lanche/alimentação?"
            />
            <InputError :message="form.errors.snack_description" />
        </div>

        <div>
            <label class="text-sm font-bold">Haverá brinde ou premiação?</label>
            <select
                v-model="form.with_gift"
                :disabled="readonly"
                class="textInput w-full text-tiny"
            >
                <option :value="null" disabled>Selecione</option>
                <option :value="true">Sim</option>
                <option :value="false">Não</option>
            </select>
            <InputError :message="form.errors.with_gift" />
        </div>
        <div>
            <textarea
                v-if="form.with_gift"
                v-model="form.gift_description"
                :disabled="readonly"
                class="textInput w-full text-tiny h-20"
                placeholder="Quais brindes/premiações?"
            />
            <InputError :message="form.errors.gift_description" />
        </div>

        <!-- CONTRIBUIÇÃO -->
        <div>
            <label class="text-sm font-bold">Solicitação de Recursos ou Notificação de Contribuição Externa/Doações</label>
            <select
                v-model="form.with_contribution"
                :disabled="readonly"
                class="textInput w-full text-tiny"
            >
                <option :value="null" disabled>Haverá solicitação de recursos ou contribuição externa?</option>
                <option :value="true">Sim</option>
                <option :value="false">Não</option>
            </select>
            <InputError :message="form.errors.with_contribution" />
        </div>
        <div>
            <textarea
                v-if="form.with_contribution"
                v-model="form.contribution_description"
                :disabled="readonly"
                class="textInput w-full text-tiny h-20"
                placeholder="Descrever solicitação/contribuição"
            />
            <InputError :message="form.errors.contribution_description" />
        </div>
    </div>
</template>
