<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import InstitutionalHeader from '@/pages/partials/InstitutionalHeader.vue';
import InputError from "@/components/InputError.vue";

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'))
}
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex flex-col">

        <!-- Cabeçalho institucional -->
        <InstitutionalHeader
            title="Acesso ao Sistema"
            subtitle="Eventos Institucionais"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <!-- Card de login -->
        <div class="flex-1 flex items-center justify-center px-4 mt-2">
            <div class="w-full max-w-sm bg-white rounded-2xl shadow-lg p-6 space-y-6">

                <h1 class="text-center text-lg font-semibold text-slate-700">
                    Entrar
                </h1>

                <div class="space-y-3">
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="E-mail"
                        class="input"
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />

                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="Senha"
                        class="input"
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />

                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" v-model="form.remember" />
                        Manter conectado
                    </label>
                </div>

                <button
                    @click="submit"
                    :disabled="form.processing"
                    class="w-full py-3 bg-blue-600 text-white rounded-xl font-semibold
                 hover:bg-blue-700 active:scale-[0.99] transition disabled:opacity-50"
                >
                    Entrar
                </button>

                <div class="text-center text-sm text-slate-500">
                    <span>Problemas para acessar?</span>
                </div>

            </div>
        </div>

        <!-- Rodapé -->
        <div class="text-center py-4">
            <span class="text-xs font-semibold text-sky-800 tracking-wide">e-Eventos</span>
        </div>

    </div>
</template>

<style scoped>
.input {
    width: 100%;
    border-radius: 0.75rem;
    padding: 0.75rem 0.75rem;
    font-size: 0.9rem;
    border: 1px solid var(--tw-border-color, #d1d5db);
}

.input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}
</style>
