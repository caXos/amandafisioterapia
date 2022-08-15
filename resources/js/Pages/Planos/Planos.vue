<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';

const props = defineProps({
    planos: Object
});

onMounted(() => {
    $('#tabela-planos').DataTable(
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
    $('select[name="tabela-planos_length"]').css('padding-right', '25px');
});
</script>
<script>
let formatBrazilianReal = Intl.NumberFormat('pt-BR');
</script>

<template>

    <Head title="Planos" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Planos
            </p>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <table id="tabela-planos">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Tempo</th>
                                    <th>Frequência</th>
                                    <th>Férias</th>
                                    <th>Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="plano in planos" :key="plano.id">
                                    <td>{{ plano.nome }}</td>
                                    <td>{{ plano.tempo }}</td>
                                    <td>{{ plano.frequencia }}</td>
                                    <td>{{ plano.ferias }}</td>
                                    <td>{{ formatBrazilianReal.format(parseFloat(plano.valorTotal)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
