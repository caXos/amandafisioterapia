<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import { onMounted, computed, ref } from 'vue';
import FisioSelectVue from '@/Components/FisioSelect.vue';
import axios from 'axios';
import FisioSelect from '@/Components/FisioSelect.vue';

const props = defineProps({
    status: String,
});

const form = useForm({
});

onMounted(function () {

});
const submit = () => {

};

</script>

<script>
function mascaraTelefone() {
    console.log('mascaraTelefone');
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

                            <!--
                                    
                                    <th>Telefone</th>
                                    <th>Nascimento</th>
                                    -->
                            <div>
                                <BreezeLabel for="nome" value="Nome" />
                                <BreezeInput id="nome" type="text" class="mt-1 block w-full" v-model="form.nome"
                                    placeholder="Nome completo do paciente" required autofocus />
                            </div>
                            <div>
                                <BreezeLabel for="plano" value="Plano" />
                                <BreezeInput id="plano" type="text" class="mt-1 block w-full" v-model="form.plano"
                                    placeholder="Aqui vai o PlanoSelect" required />
                            </div>
                            <div class="mt-4">
                                <BreezeLabel for="inicio" value="Data Início" />
                                <BreezeInput id="inicio" type="date" class="mt-1 block w-full" v-model="form.inicio"
                                    required />
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
                                <!-- <Link v-if="paciente !== null" class="inline-flex items-center ml-4 px-4 py-2 bg-rose-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:shadow-outline-rose transition ease-in-out duration-150" :href="#">
                                    Remover
                                </Link> -->
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