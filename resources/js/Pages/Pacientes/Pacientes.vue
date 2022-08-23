<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';
import FAB from '@/Components/FloatingActionButton.vue';

const props = defineProps({
    pacientes: Object,
});

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
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <FAB model="Paciente" rota="adicionarPaciente"></FAB>
                        <table id="tabela-pacientes">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Plano</th>
                                    <th>Início</th>
                                    <th>Fim</th>
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
                                    <td>{{paciente.plano_inicio}}</td>
                                    <td>{{paciente.plano_fim}}</td>
                                    <td>{{paciente.fisio_nome.split(" ")[0]}}</td>
                                    <td>{{paciente.observacao}}</td>
                                    <td>{{paciente.telefone}}</td>
                                    <td>{{paciente.nascimento}}</td>
                                    <td>
                                        <div class="grid grid-cols-2">
                                            <div>
                                            <Link :href="route('editarPaciente',[paciente.id])">
                                                <span class="material-symbols-outlined text-color-inherit cursor-pointer mx-2 hover:ring-2 hover:ring-offset-2 hover:rounded-full" title="Editar">edit</span>
                                            </Link>
                                            </div>
                                            <div>
                                            <!-- <Link :href="#"> -->
                                                <span class="material-symbols-outlined text-color-inherit cursor-pointer mx-2 hover:ring-2 hover:ring-offset-2 hover:rounded-full" title="Prontuários">assignment</span>
                                            <!-- </Link> -->
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
