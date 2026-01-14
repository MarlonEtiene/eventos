<script setup lang="ts">
import { ref } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import InstitutionalHeader from '@/Pages/Partials/InstitutionalHeader.vue'
import InputError from '@/components/InputError.vue'
import Alert from '@/components/Alert.vue'

const page = usePage();

// controla o tipo de acesso
const accessType = ref<'interno' | 'solicitante'>('interno')

/**
 * Login interno (admin / direção) - Breeze
 */
const internalForm = useForm({
    email: '',
    password: '',
    remember: false,
})

const submitInternal = () => {
    internalForm.post(route('login'), {
        onFinish: () => {
            internalForm.reset('password');
            internalForm.reset('email');
        }
    })
}

/**
 * Login solicitante (magic link)
 */
const requesterForm = useForm({
    email: '',
})

const submitRequester = () => {
    requesterForm.post(route('magic-link.store'), {
        onSuccess: () => {
            requesterForm.reset();
        }
    })
}
</script>

<template>
    <Head title="Login"/>
    <Alert />
    <div class="min-h-screen bg-slate-100 flex flex-col">
        <!-- Cabeçalho institucional -->
        <InstitutionalHeader
            title="Acesso ao Sistema"
            subtitle="Eventos Institucionais"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <!-- Card -->
        <div class="flex-1 flex items-center justify-center px-4">
            <div class="w-full max-w-sm bg-white rounded-2xl shadow-lg p-6 space-y-6">

                <!-- Tabs -->
                <div class="flex rounded-lg bg-slate-100 p-1">
                    <button
                        class="flex-1 py-2 text-sm font-medium rounded-md"
                        :class="accessType === 'interno'
                            ? 'bg-white shadow text-slate-800'
                            : 'text-slate-500'"
                        @click="accessType = 'interno'"
                    >
                        Administração
                    </button>

                    <button
                        class="flex-1 py-2 text-sm font-medium rounded-md"
                        :class="accessType === 'solicitante'
                            ? 'bg-white shadow text-slate-800'
                            : 'text-slate-500'"
                        @click="accessType = 'solicitante'"
                    >
                        Solicitação
                    </button>
                </div>

                <!-- LOGIN INTERNO -->
                <form
                    v-if="accessType === 'interno'"
                    @submit.prevent="submitInternal"
                    class="space-y-4"
                >
                    <h1 class="text-center text-lg font-semibold text-slate-700">
                        Entrar no sistema
                    </h1>

                    <div>
                        <input
                            v-model="internalForm.email"
                            type="email"
                            placeholder="E-mail"
                            class="input"
                            autocomplete="username"
                        />
                        <InputError :message="internalForm.errors.email" />
                    </div>

                    <div>
                        <input
                            v-model="internalForm.password"
                            type="password"
                            placeholder="Senha"
                            class="input"
                            autocomplete="current-password"
                        />
                        <InputError :message="internalForm.errors.password" />
                    </div>

                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" v-model="internalForm.remember" />
                        Manter conectado
                    </label>

                    <button
                        type="submit"
                        :disabled="internalForm.processing"
                        class="w-full py-3 bg-blue-600 text-white rounded-xl font-semibold
                               hover:bg-blue-700 disabled:opacity-50"
                    >
                        Entrar
                    </button>
                </form>

                <!-- LOGIN SOLICITANTE -->
                <form
                    v-else
                    @submit.prevent="submitRequester"
                    class="space-y-4"
                >
                    <h1 class="text-center text-lg font-semibold text-slate-700">
                        Acesso do Solicitante
                    </h1>

                    <p class="text-sm text-slate-500 text-center">
                        Informe seu e-mail para acessar ou acompanhar
                        suas solicitações.
                    </p>

                    <div>
                        <input
                            v-model="requesterForm.email"
                            type="email"
                            placeholder="E-mail"
                            class="input"
                        />
                        <InputError :message="requesterForm.errors.email" />
                    </div>

                    <button
                        type="submit"
                        :disabled="requesterForm.processing"
                        class="w-full py-3 bg-green-600 text-white rounded-xl font-semibold
                               hover:bg-green-700 disabled:opacity-50"
                    >
                        Enviar link de acesso
                    </button>
                </form>

            </div>
        </div>

        <!-- Rodapé -->
        <div class="text-center mt-1">
            <span class="text-sm font-semibold text-sky-800 tracking-wide">e-Eventos</span>
            <img src="/images/logo-eei.png"
                 alt="Viva Rio Logo"
                 class="h-10 mt-1 block mx-auto" />
        </div>

    </div>
</template>

<style scoped>
.input {
    width: 100%;
    border-radius: 0.75rem;
    padding: 0.75rem;
    font-size: 0.9rem;
    border: 1px solid var(--tw-border-color, #d1d5db);
}

.input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}
</style>
