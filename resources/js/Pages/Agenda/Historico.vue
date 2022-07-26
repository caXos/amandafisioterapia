<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import FAB from '@/Components/FloatingActionButton.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    compromissos: Object,
    status: String
});

onMounted(() => {
    $('#tabela-compromisso').DataTable(
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
    $('select[name="tabela-compromisso_length"]').css('padding-right', '25px');
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
                        <FAB model="Compromisso" rota="adicionarCompromisso"></FAB>
                        <p v-if="compromissos.length === 0" class="text-sky-800 text-center">Não há compromissos. Use o botão
                            abaixo para incluir compromissos na agenda.</p>
                        <table v-else id="tabela-compromisso" class="row-border text-sky-800 text-center border border-1 border-sky-300 rounded-md">
                            <thead>
                                <tr>
                                    <th>Dia</th>
                                    <th>Hora</th>
                                    <th>Compromisso</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(compromisso, index) in compromissos" :key="index" class="border border-sky-300 rounded-md hover:bg-sky-200">
                                    <td><span style="display: none;">{{ compromisso.dia }}</span>{{ new Date(compromisso.dia).toLocaleDateString() }}</td>
                                    <td>{{ compromisso.hora.substring(0, 5) }}</td>
                                    <td class="flex text-center">
                                        <div v-if="compromisso.atendimentos.length === 0"></div>
                                        <div v-else :class="`flex-1 grid grid-cols-${(compromisso.atendimentos.length % 3) + 1} grid-rows-${(compromisso.atendimentos.length % 3) + 1}`">
                                            <div class="grid grid-rows-4 border border-0 rounded-full cursor-pointer hover:bg-slate-100"
                                                v-for="(atendimento, index) in compromisso.atendimentos" :key="index">
                                                <div>{{ atendimento.paciente_nome }}</div>
                                                <div>{{ atendimento.atividade_nome }}</div>
                                                <div>{{ atendimento.aparelho_nome }}</div>
                                                <div>{{ atendimento.fisio_nome.split(" ")[0] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="flex-initial">
                                        <!-- <Link :href="route('editarAgenda', [id])"> -->
                                            <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                                                :title="'Editar atendimento'">edit</span>
                                        <!-- </Link> -->
                                        <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                                            :title="`Notificar todos`"
                                            v-on:click="notificarPaciente(this.time)">notifications</span>
                                        <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                                            title="Marcar todos como completados"
                                            v-on:click="completarCompromisso(this.id)">done</span>
                                        <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                                            :title="'Deletar todos sem completar'"
                                            v-on:click="deletarCompromisso(this.id)">delete</span>
                                        <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                                            :title="'Agendar retorno para todos'"
                                            v-on:click="agendarRetorno(this.paciente)">forward</span>
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