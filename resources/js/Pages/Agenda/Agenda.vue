<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import AgendaCard from '@/Components/AgendaCard.vue';
import FAB from '@/Components/FloatingActionButton.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { computed, ref } from 'vue';
import AgendaCardModal from '@/Components/AgendaCardModal.vue';

const props = defineProps({
    agendas: Object,
    status: String,
});

const modal = ref(false);

const modalConteudo = ref({
    'titulo': null,
    'primeiraLinha': null,
    'segundaLinha': null,
    'compromisso': null
});
function abrirNotificarAgendaToda(compromisso) {
    if (compromisso.atendimentos.length === 1) {
        modalConteudo.value.titulo = 'Notificar um(a) paciente'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja notificar o(a) paciente do seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Um paciente será notificado.'
    } else {
        modalConteudo.value.titulo = 'Notificar todos(as) os(as) pacientes'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja notificar os(as) pacientes do seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Serão notificados(as) ' + compromisso.atendimentos.length + ' pacientes.'
    }
    modalConteudo.value.compromisso = compromisso
    modal.value = true
}
function abrirCompletarAgendaToda(compromisso) {
    // compromisso.atendimentos.length === 1 ? modalConteudo.value.titulo = 'Notificar um(a) paciente' : modalConteudo.value.titulo = 'Notificar todos(as) os(as) pacientes'
    modalConteudo.value.titulo = 'Completar compromisso'
    modalConteudo.value.acao = 'completar'
    modalConteudo.value.compromisso = compromisso
    console.log(modalConteudo.value.compromisso)
    modal.value = true

}
function abrirFaltarAgendaToda(compromisso) {
    compromisso.atendimentos.length === 1 ? modalConteudo.value.titulo = 'Marcar uma falta' : modalConteudo.value.titulo = 'Marcar falta para todos'
    modalConteudo.value.acao = 'faltar'
    modalConteudo.value.compromisso = compromisso
    console.log(modalConteudo.value.compromisso)
    modal.value = true

}
/*
@faltarAgendaToda="abrirFaltarAgendaToda"
@deletarAgendaToda="abrirDeletarAgendaToda"
@retornarAgendaToda="abrirRetornarAgendaToda"
*/

function abrirModal(valor) {
    console.log(valor)
    modalConteudo.value.titulo = valor.id
    modalConteudo.value.conteudo = 'blah'
    modal.value = true
}
function fecharModal() {
    modal.value = false
}
</script>

<script>

</script>

<template>

    <Head title="Agenda" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Agenda
            </p>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <FAB model="Compromisso" rota="adicionarAgenda"></FAB>
                        <p v-if="agendas.length == 0" class="text-sky-800 text-center">Não há compromissos. Use o botão
                            abaixo para incluir compromissos na agenda.</p>
                        <AgendaCard v-else v-for="(agenda, index) in agendas" :key="index" :agenda="agenda"
                            @notificarAgendaToda="abrirNotificarAgendaToda"
                            @completarAgendaToda="abrirCompletarAgendaToda"
                            @faltarAgendaToda="abrirFaltarAgendaToda"
                            @deletarAgendaToda="abrirDeletarAgendaToda"
                            @retornarAgendaToda="abrirRetornarAgendaToda"
                            
                            />

                        <AgendaCardModal v-if="modal == true" v-on:fecharModal="fecharModal" 
                            :titulo="modalConteudo.titulo"
                            :compromisso="modalConteudo.compromisso"
                            :primeiraLinha="modalConteudo.primeiraLinha"
                            :segundaLinha="modalConteudo.segundaLinha"
                        />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>