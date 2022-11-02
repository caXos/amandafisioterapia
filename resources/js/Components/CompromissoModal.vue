<script setup>
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps([
    'titulo',
    'primeiraLinha',
    'segundaLinha',
    'compromisso',
    'rota'
]);

defineEmits(['fecharModal']);
</script>

<script>
</script>

<template>
    <div id="modal-overlay" class="z-10 absolute flex grow w-full bg-gray-100 top-0 left-0 transition-all" @click="$emit('fecharModal')">
        <div class="fixed top-1/4 left-[10%] lg:top-[15%] lg:left-1/4 h-min bg-white-100 w-5/6 lg:w-1/2 z-20 container shadow-lg shadow-sky-200 text-center text-sky-600 hover:bg-sky-100 transition-all border-2 rounded border-sky-600">
            <div class="bg-red-200 border-b-2 border-sky-600 text-bold py-2">{{titulo}}</div>
            <div class="grid grid-rows-3">
                <div>{{primeiraLinha}}</div>
                <div>{{ new Date(compromisso.dia).toLocaleDateString() }}, {{ new Date(compromisso.dia).toLocaleString('pt-BR', {weekday: 'long'}) }} - {{ compromisso.hora.substring(0, 5) }}</div>
                <div>{{segundaLinha}}</div>
            </div>
            <div class="mt-2 mb-1 grid grid-cols-2 items-center justify-center text-center">
                <Link :href="rota">
                    <span
                        class="mx-4 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                        :title="`Confirmar`">done</span>
                </Link>
                <span
                    class="mx-4 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="`Cancelar`" @click="$emit('fecharModal')">cancel</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
    #modal-overlay {
        height: 2000px !important; 
    }
</style>