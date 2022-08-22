<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import { onMounted, computed, ref } from 'vue';
import axios from 'axios';
import FisioSelect from '@/Components/FisioSelect.vue';
import PlanoSelect from '@/Components/PlanoSelect.vue';

const props = defineProps({
    status: String,
    planos: Object,
    fisios: Object, 
    paciente: Object
});

const form = useForm({
});

onMounted(function () {

});
const submit = () => {

};

</script>

<script>
function habilitaDataInicio() {
    console.log('habilita data de inicio')
    $('#inicio').removeAttr('disabled');
}
function calculaFim(evt) {
    var tempoPhp = $('#plano>option:selected').attr('tempoPHP');
    var dataInicio = $('#inicio').val();
    var dataFim = null;
    if (tempoPhp === '+1 Month') {
        dataFim = new Date(dataInicio).setMonth(new Date(dataInicio).getMonth()+1);
        dataFim = new Date(dataFim).toISOString();
        $('#fim').val(dataFim.substring(0,10));
    }
}
function mascaraTelefone(evt) {
    // console.log('mascaraTelefone', evt);
    // $('#telefone').mask('(99) 999-999-999');
}
</script>

<template>

    <Head title="Pacientes" />
    <BreezeValidationErrors class="mb-4" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Pacientes - Criar/editar paciente
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
                                <BreezeLabel for="nome" value="Nome" />
                                <BreezeInput id="nome" type="text" class="mt-1 block w-full" v-model="form.nome"
                                    placeholder="Nome completo do paciente" required autofocus />
                            </div>
                            <div>
                                <BreezeLabel for="plano" value="Plano" />
                                <PlanoSelect id="plano" class="mt-1 block w-full" v-model="form.plano" :planos="planos"
                                    :selectedIndex="planos.id" required @change="habilitaDataInicio()" />
                            </div>
                            <div class="mt-4">
                                <BreezeLabel for="inicio" value="Data Início" />
                                <BreezeInput id="inicio" type="date" class="mt-1 block w-full" v-model="form.inicio"
                                    @change="calculaFim($event)" required disabled />
                            </div>
                            <div class="mt-4">
                                <BreezeLabel for="fim" value="Data Fim" />
                                <BreezeInput id="fim" type="date" class="mt-1 block w-full" v-model="form.fim"
                                    disabled />
                            </div>
                            <div class="mt-4">
                                <BreezeLabel for="fisio" value="Fisioterapeuta" />
                                <FisioSelect id="fisio" class="mt-1 block w-full" v-model="form.fisio" :fisios="fisios"
                                    :selectedIndex="fisio_id" required />
                            </div>
                            <div>
                                <BreezeLabel for="observacao" value="Observação" />
                                <BreezeInput id="observacao" type="text" class="mt-1 block w-full"
                                    v-model="form.observacao" placeholder="Alguma informação relevante" required />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="telefone" value="Telefone" />
                                <BreezeInput id="telefone" class="mt-1 block w-full" type="text" v-model="form.telefone"
                                    pattern="\(99\) 999-999-999" data-mask="(99) 999-999-999" placeholder='(99) 999-999-999' 
                                    @change="mascaraTelefone($event)" required />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="nascimento" value="Data Nascimento" />
                                <BreezeInput id="nascimento" type="date" class="mt-1 block w-full" v-model="form.nascimento"
                                    required />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:shadow-outline-slate transition ease-in-out duration-150" :href="route('pacientes')">
                                    Voltar
                                </Link>
                                <Link v-if="paciente !== undefined" class="inline-flex items-center ml-4 px-4 py-2 bg-rose-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:shadow-outline-rose transition ease-in-out duration-150" :href="route('pacientes')">
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