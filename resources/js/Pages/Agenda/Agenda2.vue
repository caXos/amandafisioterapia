<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import AgendaCard from '@/Components/AgendaCard.vue';
import FAB from '@/Components/FloatingActionButton.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { computed, onMounted } from 'vue';

const props = defineProps({
    agendas: Object,
    status: String
});

onMounted(() => {
    console.log(props.agendas);
    $('#tabela-agenda').DataTable(
        {
            language: {
                processing: "Processando...",
                search: "Pesquisar:",
                lengthMenu: "Quantos elementos mostrar _MENU_",
                info: "Mostrando os elementos de _START_ a _END_ de um total de _TOTAL_ ",
                infoEmpty: "Mostrando os elementos de 0 a 0 de um total de 0",
                infoFiltered: "(filtrados de um total de _MAX_ elementos)",
                infoPostFix: "",
                loadingRecords: "Carregando...",
                zeroRecords: "Nada para mostrar",
                emptyTable: "Não há nenhum atendimento!",
                paginate: {
                    first: "Primeira",
                    previous: "Anterior",
                    next: "Próxima",
                    last: "Última"
                },
                aria: {
                    sortAscending: ": Ordem crescente",
                    sortDescending: ": Ordem decrescente"
                }
            },
            responsive: true,
        }
    );
    $('select[name="tabela-agenda_length"]').css('padding-right','25px');
});

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
                        <p v-if="agendas.length == 0" class="text-sky-800 text-center">Não há compromissos. Use o botão abaixo para incluir compromissos na agenda.</p>
                        <table v-else id="tabela-agenda">
                            <thead>
                                <tr>
                                    <th>Dia</th>
                                    <th>Hora</th>
                                    <th>Compromisso</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(agenda, index) in agendas" :key="index">
                                    <td>{{agenda.dia}}</td>
                                    <td>{{agenda.hora.substring(0,5)}}</td>
                                    <td class="flex">
                                        <div v-if="agenda.atendimentos.length === 0"></div>
                                        <div v-else :class="{
                                            'grid grid-cols-1' : agenda.atendimentos.length === 1
                                            , 'grid grid-cols-2' : agenda.atendimentos.length === 2
                                            , 'grid grid-cols-3' : agenda.atendimentos.length === 3
                                        }">
                                            <div class="grid grid-rows-4">
                                                <div>{{agenda.atendimentos[0].paciente_nome}}</div>
                                                <div>{{agenda.atendimentos[0].atividade_nome}}</div>
                                                <div>{{agenda.atendimentos[0].aparelho_nome}}</div>
                                                <div>{{agenda.atendimentos[0].fisio_nome.split(" ")[0]}}</div>
                                            </div>
                                            <div v-if="agenda.atendimentos.length === 2" class="grid grid-rows-4">
                                                <div>{{agenda.atendimentos[1].paciente_nome}}</div>
                                                <div>{{agenda.atendimentos[1].atividade_nome}}</div>
                                                <div>{{agenda.atendimentos[1].aparelho_nome}}</div>
                                                <div>{{agenda.atendimentos[1].fisio_nome.split(" ")[0]}}</div>
                                            </div>
                                            <div v-else-if="agenda.atendimentos.length === 3" class="grid grid-rows-4">
                                                <div>{{agenda.atendimentos[2].paciente_nome}}</div>
                                                <div>{{agenda.atendimentos[2].atividade_nome}}</div>
                                                <div>{{agenda.atendimentos[2].aparelho_nome}}</div>
                                                <div>{{agenda.atendimentos[2].fisio_nome.split(" ")[0]}}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Acoes</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>