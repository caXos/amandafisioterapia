<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import TableRow from '@/Components/TableRow.vue';
import { Head } from '@inertiajs/inertia-vue3';

defineProps({
    lancamentos: Object,
    soma: Number,
    loggedUser: Number
});
</script>

<script>
$(document).ready(function () {
    $('#tabela-financeiro').DataTable(
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
            }
        }
    );
});

let formatBrazilianReal = Intl.NumberFormat('pt-BR');

</script>

<template>

    <Head title="Financeiro" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Financeiro
            </h2>
        </template>

        <div class="py-12 justify-center align-center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <table id="tabela-financeiro">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <th>Descrição</th>
                                    <th>Detalhe</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <TableRow v-for="lancamento in lancamentos" 
                                :key="lancamento.id" 
                                :date="lancamento.date"
                                :time="lancamento.time" 
                                :description="lancamento.description" 
                                :detail="lancamento.detail"
                                :type="lancamento.type" 
                                :value="lancamento.value" />
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="dt-right">Subtotal:</td>
                                    <td class="espaco-entre" :class="{
                                                                'bg-green-100 !important hover:bg-green-100 hover:text-green-900': soma >= 0
                                                                ,'bg-red-100 !important hover:bg-red-100 hover:text-red-900': soma < 0
                                                                }">
                                        <span>R$</span><span> {{ formatBrazilianReal.format(soma) }}</span>
                                    </td>
                                    <td class="dt-center">
                                        <!-- <span class="material-symbols-outlined">edit</span> -->
                                        <span class="material-symbols-outlined" style="cursor:pointer;" title="Deletar todos">delete</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style scoped>
.espaco-entre {
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between !important;
}
</style>