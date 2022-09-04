<script setup>
const props = defineProps([
    'atendimento',
    'dia',
    'hora',
    'onFecharModalAtendimento'
]);

defineEmits(['fecharModalAtendimento']);
</script>

<script>
</script>

<template>
    <div id="modal-overlay" class="z-10 absolute flex grow w-full bg-gray-100 top-0 left-0 transition-all" @click="$emit('fecharModalAtendimento')">
        <div class="fixed top-1/4 left-[10%] lg:top-[15%] lg:left-1/4 h-min bg-white-100 w-5/6 lg:w-1/2 z-20 container shadow-lg shadow-sky-200 text-center text-sky-600 hover:bg-sky-100 transition-all border-2 rounded border-sky-600">
            <div class="bg-sky-100">Ações para atendimento</div>
            <div class="grid grid-rows-5">
                <div>{{ new Date(dia).toLocaleDateString() }}, {{ new Date(dia).toLocaleString('pt-BR', {weekday: 'long'}) }} - {{ hora.substring(0, 5) }}</div>
                <div>{{atendimento.paciente_nome}}</div>
                <div>{{atendimento.atividade_nome}}</div>
                <div>{{atendimento.aparelho_nome}}</div>
                <div>{{atendimento.fisio_nome}}</div>
            </div>
            <div class="mt-2 mb-1">
                <!-- <Link :href="route('editarAgenda', [agenda.id])"> -->
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="`Editar atendimento`" >edit</span>
                <!-- </Link> -->
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="`Notificar paciente`" @click="$emit('notificarAgendaToda', agenda)">notifications</span>
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    title="Marcar como completado" @click="$emit('completarAgendaToda',agenda)">done_all</span>
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Marcar com falta'"
                    @click="$emit('faltarAgendaToda', agenda)">event_busy</span>
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Deletar sem completar'" @click="$emit('deletarAgendaToda', agenda)">delete</span>
                <span class="mx-4 material-symbols-outlined text-color-inherit mx-1 cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Agendar Retorno'" @click="$emit('retornarAgendaToda', agenda)">forward</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
    #modal-overlay {
        height: 2000px !important; 
    }
</style>