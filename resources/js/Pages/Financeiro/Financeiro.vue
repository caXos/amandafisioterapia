<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import FinanceiroTableRow from '@/Components/FinanceiroTableRow.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
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
                emptyTable: "Não há nenhum lançamento!",
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
                        <div>
                                <BreezeLabel for="subtotal" value="Subtotal" />
                                <!-- <BreezeInput id="subtotal" type="text" :class="{
                                    'mt-1 block w-full text-center bg-green-100 text-green-900 border-green-600 mb-2': resultado > 0
                                    ,'mt-1 block w-full text-center bg-amber-100 text-amber-900 border-amber-600 mb-2': resultado === 0
                                    ,'mt-1 block w-full text-center bg-red-100 text-red-900 border-red-600 mb-2': resultado < 0
                                    }" disabled :value="`R$ ${formatBrazilianReal.format(resultado)}`" /> -->
                                <BreezeInput id="subtotal" type="text" :class="{
                                    'mt-1 block w-full text-center bg-green-100 text-green-900 border-green-600 mb-2': parseFloat(resultado.replace(',','.')) > 0
                                    ,'mt-1 block w-full text-center bg-amber-100 text-amber-900 border-amber-600 mb-2': parseFloat(resultado.replace(',','.')) === 0
                                    ,'mt-1 block w-full text-center bg-red-100 text-red-900 border-red-600 mb-2': parseFloat(resultado.replace(',','.')) < 0
                                    }" disabled :value="`R$ ${resultado}`" />
                            </div>
                        <FAB model="Lançamento" rota="adicionarFinanceiro"></FAB>
                        <table id="tabela-financeiro" class="text-sky-800">
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style scoped>

</style>