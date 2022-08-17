<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import FinanceiroTableRow from '@/Components/FinanceiroTableRow.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';
import FAB from '@/Components/FloatingActionButton.vue';

const props = defineProps({
    lancamentos: Object,
    resultado: Number,
    status: String
});

onMounted(() => {
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
    $('select[name="tabela-financeiro_length"]').css('padding-right','25px');
});
</script>

<script>
let formatBrazilianReal = Intl.NumberFormat('pt-BR');
</script>

<template>

    <Head title="Financeiro" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Financeiro
            </p>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
        </template>

        <div class="py-2 justify-center align-center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <FAB model="Lançamento" rota="adicionarFinanceiro"></FAB>
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
                                <FinanceiroTableRow v-for="lancamento in lancamentos" :key="lancamento.id" :lancamento="lancamento"/>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="dt-right">Subtotal:</td>
                                    <td class="espaco-entre" :class="{
                                        'bg-green-100 !important hover:bg-green-100 hover:text-green-900': resultado >= 0
                                        , 'bg-red-100 !important hover:bg-red-100 hover:text-red-900': resultado < 0
                                    }">
                                        <span>R$</span><span> {{ formatBrazilianReal.format(resultado) }}</span>
                                    </td>
                                    <td class="dt-center">
                                        <!-- <span class="material-symbols-outlined">edit</span> -->
                                        <span class="material-symbols-outlined" style="cursor:pointer;"
                                            title="Deletar todos">delete</span>
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