<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import { onMounted, computed, ref } from 'vue';
import FinanceiroTipoSelect from '@/Components/FinanceiroTipoSelect.vue';
import axios from 'axios';

const props = defineProps({
    financeiro: Object,
    dataAtual: String,
    horaAgora: String
});

const form = useForm({
    dia: Date,
    hora: Date,
    descricao: String,
    detalhe: String,
    tipo: Number,
    valor: Number,
});

const finan = ref(props.financeiro);
const da = ref(props.dataAtual);

onMounted(function () {
    if (props.financeiro == null || props.financeiro == undefined || props.financeiro == '' || props.financeiro.length == 0) {
        //Nao ha nenhum lancamento, ou seja, e para adicionar novo lancamento
        $('#dia').val('');
        $('#hora').val('');
        $('#descricao').val('');
        $('#detalhe').val('');
        $('#tipo').val('');
        $('#valor').val('');
    } else if (props.financeiro !== null && props.financeiro !== undefined && props.financeiro !== '') {
        //Recebeu um lancamento para edicao
        $('#dia').val(props.financeiro.dia);
        $('#hora').val(props.financeiro.hora);
        $('#descricao').val(props.financeiro.descricao);
        $('#detalhe').val(props.financeiro.detalhe);
        $('#tipo').val(props.financeiro.tipo);
        $('#valor').val(props.financeiro.valor);
    }
    // if (finan._rawValue !== null && finan._rawValue !== undefined && finan._rawValue !== '') {
    //     $('#dia').val(finan._rawValue.dia)
    //     $('#hora').val(finan._rawValue.hora)
    //     $('#descricao').val(finan._rawValue.descricao)
    //     $('#detalhe').val(finan._rawValue.detalhe)
    //     $('#tipo').val(finan._rawValue.tipo)
    //     $('#valor').val(finan._rawValue.valor)
    // }
    // if (props.financeiro !== null && props.financeiro !== undefined && props.financeiro !== '') {
    //     $('#dia').val(finan._rawValue.dia).trigger('input')
    //     $('#hora').val(finan._rawValue.hora).trigger('input')
    //     $('#descricao').val(finan._rawValue.descricao).trigger('input')
    //     $('#detalhe').val(finan._rawValue.detalhe).trigger('input')
    //     $('#tipo').val(finan._rawValue.tipo).trigger('input')
    //     $('#valor').val(finan._rawValue.valor).trigger('input')
    // }
});
const submit = () => {
    if (props.financeiro == null) {
        form.post(route('gravarFinanceiro'), {
            onFinish: () => {
                $('#tipo').val('0')
                form.reset()
            }
        });
    } else {
        form.dia = $('#dia').val();
        form.hora = $('#hora').val();
        form.descricao = $('#descricao').val();
        form.detalhe = $('#detalhe').val();
        form.tipo = $('#tipo').val();
        form.valor = $('#valor').val();
        form.post(route('editarFinanceiro', [props.financeiro.id]), {
            onFinish: () => {
                $('#tipo').val('0')
                form.reset()
            }
        });
    }

};

</script>

<script>

</script>

<template>

    <Head title="Financeiro" />
    <BreezeValidationErrors class="mb-4" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Financeiro - Criar/editar lançamento
            </p>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <BreezeLabel for="dia" value="Dia" />
                                <BreezeInput id="dia" type="date" class="mt-1 block w-full" v-model="form.dia" required
                                    autofocus />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="hora" value="Hora" />
                                <BreezeInput id="hora" type="time" class="mt-1 block w-full" v-model="form.hora"
                                    required />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="descricao" value="Descrição" />
                                <BreezeInput id="descricao" class="mt-1 block w-full" type="text"
                                    v-model="form.descricao" required />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="detalhe" value="Detalhe" />
                                <BreezeInput id="detalhe" type="text" class="mt-1 block w-full"
                                    v-model="form.detalhe" />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="tipo" value="Tipo" />
                                <FinanceiroTipoSelect id="tipo" class="mt-1 block w-full" v-model="form.tipo"
                                    required />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="valor" value="Valor" />
                                <BreezeInput id="valor" type="number" step="0.01" class="mt-1 block w-full"
                                    v-model="form.valor" required />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:shadow-outline-slate transition ease-in-out duration-150" :href="route('financeiro')">
                                    Voltar
                                </Link>
                                <Link v-if='financeiro != null' class="inline-flex items-center ml-4 px-4 py-2 bg-rose-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:shadow-outline-rose transition ease-in-out duration-150" :href="route('deletarFinanceiro',[financeiro.id])">
                                    Remover
                                </Link>
                                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Salvar
                                </BreezeButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>