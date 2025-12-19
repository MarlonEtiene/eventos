<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();
const visible = ref(true);

const message = computed(() => page.props.flash?.status);

watch(message, () => {
    visible.value = true
    setTimeout(() => {
        visible.value = false
    }, 5000)
})
</script>

<template>
    <transition name="fade">
        <div
            v-if="message && visible"
            class="mb-4 rounded-xl bg-green-50 border border-green-200
                   text-green-800 px-4 py-3 text-sm text-center"
        >
            {{ message }}
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
