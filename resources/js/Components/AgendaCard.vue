<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue'

const props = defineProps({
    agenda: Object,
    onEditarAgendaToda: Function
});

defineEmits(['editarAgendaToda, notificarAgendaToda, completarAgendaToda, faltarAgendaToda, deletarAgendaToda, retornarAgendaToda']);

</script>

<script>
</script>

<template>
    <div
        class="container shadow-lg shadow-sky-200 text-center text-sky-600 hover:bg-sky-100 transition-all border-2 rounded border-sky-600 mb-5">
        <div class="bg-sky-100">{{ new Date(agenda.dia).toLocaleDateString() }}, {{ new Date(agenda.dia).toLocaleString('pt-BR', {weekday: 'long'}) }} - {{ agenda.hora.substring(0, 5) }}
        </div>
        <div v-if="agenda.atendimentos.length === 0">
            <span class="material-symbols-outlined text-color-inherit m-1 cursor-pointer"
                :title="'Adicionar compromisso e atendimentos'">add</span>
        </div>
        <div v-else :class="{
            'flex-1 grid grid-cols-1': agenda.atendimentos.length === 1
            , 'flex-1 grid grid-cols-2': agenda.atendimentos.length === 2
            , 'flex-1 grid grid-cols-3': agenda.atendimentos.length === 3
        }">
            <div class="grid grid-rows-4 border border-0 rounded-full cursor-pointer hover:bg-sky-200"
                v-for="(atendimento, index) in agenda.atendimentos" :key="index">
                <div>{{ atendimento.paciente_nome }}</div>
                <div>{{ atendimento.atividade_nome }}</div>
                <div>{{ atendimento.aparelho_nome }}</div>
                <div>{{ atendimento.fisio_nome.split(" ")[0] }}</div>
            </div>
        </div>
        <div v-if="agenda.atendimentos.length > 0">
            <div class="mt-2 mb-1">
                <!-- <Link :href="route('editarAgenda', [id])"> -->
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="`Editar compromisso`" @click="$emit('editarAgendaToda', agenda)">edit</span>
                <!-- </Link> -->
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="`Notificar todos`" @click="$emit('notificarAgendaToda', agenda.id)">notifications</span>
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    title="Marcar como completado" @click="completarCompromissoTodo(agenda.id)">done_all</span>
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Marcar todos com falta'"
                    @click="$emit('faltarAgendaToda', agenda.id)">event_busy</span>
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Deletar sem completar'" @click="$emit('deletarAgendaToda', agenda.id)">delete</span>
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Agendar Retorno'" @click="$emit('retornarAgendaToda', agenda.id)">forward</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>