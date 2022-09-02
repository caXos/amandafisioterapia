<script setup>
// import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue'

const props = defineProps([
    'titulo',
    'conteudo',
    'botoes'
]);

defineEmits(['fecharModal']);
</script>

<script>

</script>

<template>
    <div id="modal-overlay" class="z-10 absolute flex grow w-full blah bg-gray-100 top-0 left-0 transition-all" @click="$emit('fecharModal')">
        <div
            class="fixed top-1/4 left-[10%] lg:top-[15%] lg:left-1/4 h-min bg-white-100 w-5/6 lg:w-1/2 z-20 container shadow-lg shadow-sky-200 text-center text-sky-600 hover:bg-sky-100 transition-all border-2 rounded border-sky-600">
            <div class="bg-sky-100">{{titulo}}</div>
            Tem certeza de que quer completar o seguinte compromisso?
            <div class="grid grid-rows-4">
                <div>Nome Paciente</div>
                <div>Nome Atividade</div>
                <div>Nome Aparelho</div>
                <div>conteudo</div>
            </div>
            <div class="mt-2 mb-1">
                <span
                    class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="`Confirmar`">done</span><span class="hidden">@click="$emit('notificarAgendaToda',
                    agenda.id)"</span>
                <span
                    class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
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