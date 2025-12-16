<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3'
import InstitutionalHeader from '@/pages/partials/review/InstitutionalHeader.vue'
import InputError from '@/components/InputError.vue'

defineProps<{
    status?: string
}>()

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex flex-col">

        <Head title="Login" />

        <!-- Cabeçalho institucional -->
        <InstitutionalHeader
            title="Acesso ao Sistema"
            subtitle="Eventos Institucionais"
            hospital="Hospital Municipal Albert Schweitzer"
        />

        <!-- Card -->
        <div class="flex-1 flex items-center justify-center px-4">
            <div class="w-full max-w-sm bg-white rounded-2xl shadow-lg p-6 space-y-6">

                <h1 class="text-center text-lg font-semibold text-slate-700">
                    Entrar
                </h1>

                <div
                    v-if="status"
                    class="text-sm text-center text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Email -->
                    <div class="space-y-1">
                        <label class="text-sm text-slate-600">E-mail</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="input"
                            required
                            autofocus
                            autocomplete="email"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- Senha -->
                    <div class="space-y-1">
                        <label class="text-sm text-slate-600">Senha</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="input"
                            required
                            autocomplete="current-password"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <!-- Remember -->
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" v-model="form.remember" />
                        Manter conectado
                    </label>

                    <!-- Botão -->
                    <button
                        type="submit"
                        class="w-full py-3 bg-blue-600 text-white rounded-xl font-semibold
                   hover:bg-blue-700 transition disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Entrar
                    </button>

                </form>

            </div>
        </div>

        <!-- Rodapé -->
        <div class="text-center py-4">
      <span class="text-xs font-semibold text-sky-800 tracking-wide">
        e-Same
      </span>
        </div>

    </div>
</template>

<style scoped>
.input {
    width: 100%;
    border-radius: 0.75rem;
    padding: 0.75rem;
    font-size: 0.9rem;
    border: 1px solid #d1d5db;
}

.input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}
</style>

