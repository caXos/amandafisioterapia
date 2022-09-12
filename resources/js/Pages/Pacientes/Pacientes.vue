<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { onMounted, ref } from 'vue';
import FAB from '@/Components/FloatingActionButton.vue';

const props = defineProps({
    pacientes: Object,
    status: String
});

const statusLocal = ref(props.status);

onMounted(() => {
    $('#tabela-pacientes').DataTable(
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
                emptyTable: "Não há nenhum paciente cadastrado!",
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
    $('select[name="tabela-pacientes_length"]').css('padding-right','25px');
});
</script>

<template>
    <Head title="Pacientes" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Pacientes
            </p>
            <div v-if="statusLocal != undefined" class="mb-4 font-medium text-sm text-green-600">
                {{ statusLocal }}
            </div>
            <div v-if="statusLocal === 'erro'" class="mb-4 font-medium text-sm text-red-600">
                {{ statusLocal }}
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <FAB model="Paciente" rota="adicionarPaciente"></FAB>
                        <table id="tabela-pacientes" class="text-sky-800">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Plano</th>
                                    <th>Início</th>
                                    <th>Fim</th>
                                    <th>
                                        <div class="grid grid-rows-2"> 
                                            <div>Atendimentos</div>
                                            <div title="Total / Agendados / Cumpridos / Faltas / Restantes">T/Ag/C/F/R</div>
                                        </div>
                                    </th>
                                    <th>Físio</th>
                                    <th>Observação</th>
                                    <th>Telefone</th>
                                    <th>Nascimento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="paciente in pacientes" :key="paciente.id">
                                    <td>{{paciente.nome}}</td>
                                    <td>{{paciente.plano_nome}}</td>
                                    <td><span class="hidden">{{paciente.plano_inicio}}</span>{{ new Date(new Date(paciente.plano_inicio).setDate(new Date(paciente.plano_inicio).getDate()+1)).toLocaleDateString() }}</td>
                                    <td><span class="hidden">{{paciente.plano_fim}}</span>{{ new Date(new Date(paciente.plano_fim).setDate(new Date(paciente.plano_fim).getDate()+1)).toLocaleDateString() }}</td>
                                    <td :title="`Presenças: ${paciente.atendimentos_cumpridos/paciente.atendimentos * 100}% - Faltas: ${paciente.atendimentos_faltados/paciente.atendimentos * 100}%`">{{paciente.atendimentos}}/{{paciente.atendimentos_agendados}}/{{paciente.atendimentos_cumpridos}}/{{paciente.atendimentos_faltados}}/{{ (paciente.atendimentos - (paciente.atendimentos_agendados+paciente.atendimentos_cumpridos+paciente.atendimentos_faltados) ) }}</td>
                                    <td>{{paciente.fisio_nome.split(" ")[0]}}</td>
                                    <td>{{paciente.observacao}}</td>
                                    <td>{{paciente.telefone}}</td>
                                    <td><span class="hidden">{{paciente.nascimento}}</span>{{ new Date(new Date(paciente.nascimento).setDate(new Date(paciente.nascimento).getDate()+1)).toLocaleDateString() }}</td>
                                    <td>
                                        <div class="grid grid-cols-2">
                                            <div>
                                                <Link :href="route('editarPaciente',[paciente.id])">
                                                    <span class="material-symbols-outlined text-color-inherit cursor-pointer mx-2 hover:ring-2 hover:ring-offset-2 hover:rounded-full" title="Editar">edit</span>
                                                </Link>
                                            </div>
                                            <div>
                                                <Link :href="route('prontuariosPaciente', [paciente.id])">
                                                    <span class="material-symbols-outlined text-color-inherit cursor-pointer mx-2 hover:ring-2 hover:ring-offset-2 hover:rounded-full" title="Prontuários">assignment</span>
                                                </Link>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
