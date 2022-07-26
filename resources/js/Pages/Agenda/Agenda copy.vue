<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import AgendaCard from '@/Components/AgendaCard.vue';
import FAB from '@/Components/FloatingActionButton.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { computed, ref } from 'vue';
import AgendaCardModal from '@/Components/AgendaCardModal.vue';
import AtendimentoModal from '@/Components/AtendimentoModal.vue';

const props = defineProps({
    agendas: Object,
    status: String,
});

const modal = ref(false);
const modalAtendimento = ref(false);

const modalConteudo = ref({
    'titulo': null,
    'primeiraLinha': null,
    'segundaLinha': null,
    'compromisso': null
});

const modalAtendimentoConteudo = ref({
    'atendimento': null,
    'dia': null,
    'hora': null
});

function abrirNotificarAgendaToda(compromisso) {
    if (compromisso.atendimentos.length === 1) {
        modalConteudo.value.titulo = 'Notificar paciente'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja notificar o(a) paciente do seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Um paciente será notificado.'
    } else {
        modalConteudo.value.titulo = 'Notificar pacientes'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja notificar os(as) pacientes do seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Serão notificados(as) ' + compromisso.atendimentos.length + ' pacientes.'
    }
    modalConteudo.value.compromisso = compromisso
    modal.value = true
}

function abrirCompletarAgendaToda(compromisso) {
    if (compromisso.atendimentos.length === 1) {
        modalConteudo.value.titulo = 'Registrar atendimento completado'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja registrar o seguinte compromisso como completado?'
        modalConteudo.value.segundaLinha = 'Um atendimento será marcado como completado.'
    } else {
        modalConteudo.value.titulo = 'Registrar atendimentos completados'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja registrar o seguinte compromisso como completado?'
        modalConteudo.value.segundaLinha = 'Serão registrados como completado ' + compromisso.atendimentos.length + ' atendimentos.'
    }
    modalConteudo.value.compromisso = compromisso
    modal.value = true
}

function abrirFaltarAgendaToda(compromisso) {
    if (compromisso.atendimentos.length === 1) {
        modalConteudo.value.titulo = 'Registrar falta'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja registrar o seguinte compromisso como falta?'
        modalConteudo.value.segundaLinha = 'Um atendimento será marcado como falta.'
    } else {
        modalConteudo.value.titulo = 'Registrar faltas'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja registrar o seguinte compromisso como falta?'
        modalConteudo.value.segundaLinha = 'Serão registrados como falta ' + compromisso.atendimentos.length + ' atendimentos.'
    }
    modalConteudo.value.compromisso = compromisso
    modal.value = true
}

function abrirDeletarAgendaToda(compromisso) {
    if (compromisso.atendimentos.length === 1) {
        modalConteudo.value.titulo = 'Desmarcar atendimento'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja desmarcar o seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Um atendimento será desmarcado.'
    } else {
        modalConteudo.value.titulo = 'Desmarcar atendimentos'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja desmarcar o seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Serão desmarcados ' + compromisso.atendimentos.length + ' atendimentos.'
    }
    modalConteudo.value.compromisso = compromisso
    modal.value = true
}

function abrirRetornarAgendaToda(compromisso) {
    if (compromisso.atendimentos.length === 1) {
        modalConteudo.value.titulo = 'Agendar retorno'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja agendar retorno do seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Será agendado retorno para um atendimento.'
    } else {
        modalConteudo.value.titulo = 'Agendar retornos'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja agendar retorno do seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Serão agendados retornos para ' + compromisso.atendimentos.length + ' atendimentos.'
    }
    modalConteudo.value.compromisso = compromisso
    modal.value = true
}

function abrirModalAtendimento(atendimento) {
    modalAtendimentoConteudo.value.atendimento = atendimento[0]
    modalAtendimentoConteudo.value.dia = atendimento[1]
    modalAtendimentoConteudo.value.hora = atendimento[2]
    modalAtendimento.value = true;
}

function fecharModal() {
    modal.value = false
}

function fecharModalAtendimento() {
    modalAtendimento.value = false
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

                            @abrirModalAtendimento="abrirModalAtendimento"
                            />
                        <AgendaCardModal v-if="modal == true" v-on:fecharModal="fecharModal" 
                            :titulo="modalConteudo.titulo"
                            :compromisso="modalConteudo.compromisso"
                            :primeiraLinha="modalConteudo.primeiraLinha"
                            :segundaLinha="modalConteudo.segundaLinha"
                            />
                        <AtendimentoModal v-if="modalAtendimento == true"
                            :atendimento="modalAtendimentoConteudo.atendimento"
                            :dia="modalAtendimentoConteudo.dia"
                            :hora="modalAtendimentoConteudo.hora"
                            @fecharModalAtendimento="fecharModalAtendimento"
                            />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>