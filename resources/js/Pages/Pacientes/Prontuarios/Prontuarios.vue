<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';
import FAB from '@/Components/FloatingActionButton.vue';

const props = defineProps({
    paciente: Object,
    prontuarios: Object,
});

onMounted(() => {
    $('#tabela-prontuarios').DataTable(
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
                emptyTable: "Não há nenhum prontuário cadastrado!",
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
    $('select[name="tabela-prontuarios_length"]').css('padding-right','25px');
});
</script>

<template>
    <Head title="Prontuários" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Prontuários - {{paciente.nome}}
            </p>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <FAB model="Prontuário" rota="adicionarProntuario" :args="paciente.id"></FAB>
                        <table id="tabela-prontuarios">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Horário</th>
                                    <th>Descrição</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="prontuario in prontuarios" :key="prontuario.id">
                                    <td>{{prontuario.dia}}</td>
                                    <td>{{prontuario.hora}}</td>
                                    <td>{{prontuario.descricao.substring(0,150)}}...</td>
                                    <td>
                                        <div class="grid grid-cols-2">
                                            <div>
                                                <Link :href="route('editarProntuario', [paciente.id, prontuario.id])">
                                                    <span class="material-symbols-outlined text-color-inherit cursor-pointer mx-2 hover:ring-2 hover:ring-offset-2 hover:rounded-full" title="Editar">edit</span>
                                                </Link>
                                            </div>
                                            <div>
                                                <Link :href="route('pacientes')">
                                                    <span class="material-symbols-outlined text-color-inherit cursor-pointer mx-2 hover:ring-2 hover:ring-offset-2 hover:rounded-full" title="Ver íntegra">visibility</span>
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
