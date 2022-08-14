<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';

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
                zeroRecords: "Nada para sugestões paramostrar",
                emptyTable: "Não há nenhuma sugestão nesta tabela!",
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pacientes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
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
                                    <!-- <th>{{paciente.fisio_id}}</th> -->
                                    <td>{{paciente.fisio_nome}}</td>
                                    <td>
                                        <tr><td>p1</td></tr>
                                        <tr><td>p2</td></tr>
                                        <tr><td>p3{{paciente.observacao}}</td></tr>
                                    </td>
                                    <td>{{paciente.telefone}}</td>
                                    <td>{{paciente.nascimento}}</td>
                                    <td>Ações</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
