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
    modal: Boolean
});

const modalAberta = ref(false);
const modalConteudo = ref({
    'titulo': null,
    'conteudo': null,
    'botoes': null
});
</script>

<script>
function abrirModal() {
    this.modalConteudo.titulo = 'Título Teste';
    this.modalConteudo.conteudo = 'Conteúdo Teste';
    this.modalAberta = true;
}
function fecharModal() {
    this.modalAberta = false;
}
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
                            v-on:editarAgendaToda="abrirModal()" />
                        <!-- <AgendaCardModal :class="{
                            'hidden': localModalAberta === false
                            , 'block': localModalAberta === true
                        }" v-on:fecharModal="fecharModal" /> -->
                        <!-- <AgendaCardModal v-if="modalAberta == true" v-on:fecharModal="fecharModal" /> -->
                        <AgendaCardModal v-if="modalAberta == true" v-on:fecharModal="fecharModal()" 
                            :titulo="modalConteudo.titulo"
                            :conteudo="modalConteudo.conteudo"
                            :botoes="modalConteudo.botoes"
                        />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>