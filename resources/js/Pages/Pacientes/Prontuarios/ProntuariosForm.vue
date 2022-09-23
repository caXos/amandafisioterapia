<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import { onMounted, computed, ref } from 'vue';

const props = defineProps({
    paciente: Object,
    prontuario: Object,
});

const form = useForm({
    dia: Date,
    hora: Date,
    descricao: String,
    paciente_id: Number
});

onMounted(function () {
    if (props.prontuario == null || props.prontuario == undefined || props.prontuario == '' || props.prontuario.length == 0) {
        $('#dia').val('');
        $('#hora').val('');
        $('#descricao').val('');
    } else {
        $('#dia').val(props.prontuario.dia);
        $('#hora').val(props.prontuario.hora);
        $('#descricao').val(props.prontuario.descricao);
    }
});
const submit = () => {
    if (props.prontuario == null || props.prontuario == undefined || props.prontuario == '' || props.prontuario.length == 0) {
        form.paciente_id = props.paciente.id;
        if (form.dia == null || form.dia == undefined) form.dia = $('#dia').val();
        if (form.hora == null || form.hora == undefined) form.hora = $('#hora').val();
        if (form.descricao == null || form.descricao == undefined) form.descricao = $('#descricao').val();
        form.post(route('gravarProntuario', [props.paciente.id]), {
            onSuccess: () => {
                Swal.fire ({
                    title: 'Sucesso',
                    icon: 'success',
                    text: 'Prontuário criado!'
                })
            },
            onError: () => {
                Swal.fire ({
                    title: 'Erro',
                    icon: 'error',
                    text: 'Erro ao criar prontuário'
                })
            },
            onFinish: () => {
                form.reset()
            }
        });
    } else {
        form.paciente_id = props.paciente.id;
        form.dia = $('#dia').val();
        form.hora = $('#hora').val();
        form.descricao = $('#descricao').val();
        form.post(route('editarProntuario', [props.paciente.id, props.prontuario.id]), {
            onSuccess: () => {
                Swal.fire ({
                    title: 'Sucesso',
                    icon: 'success',
                    text: 'Prontuário editado!'
                })
            },
            onError: () => {
                Swal.fire ({
                    title: 'Erro',
                    icon: 'error',
                    text: 'Erro ao editar prontuário'
                })
            },
            onFinish: () => {
                form.reset()
            }
        });
    }
};

</script>

<script>

</script>

<template>

    <Head title="Prontuários" />
    <BreezeValidationErrors class="mb-4" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Prontuários - {{ paciente.nome }} - Criar/editar prontuário
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
                            <div class="lg:grid lg:grid-cols-2">
                                <div class="lg:pr-2">
                                    <BreezeLabel for="dia" value="Dia" />
                                    <BreezeInput id="dia" type="date" class="mt-1 block w-full" v-model="form.dia"
                                        required />
                                </div>
                                <div class="mt-4 lg:pl-2 lg:mt-0">
                                    <BreezeLabel for="hora" value="Hora" />
                                    <BreezeInput id="hora" type="time" class="mt-1 block w-full" v-model="form.hora"
                                        required />
                                </div>
                            </div>
                            <div class="mt-4">
                                <BreezeLabel for="descricao" value="Descrição" />
                                <textarea id="descricao" rows="5"
                                    class="mt-1 block w-full border-cyan-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    v-model="form.descricao" required></textarea>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:shadow-outline-slate transition ease-in-out duration-150"
                                    :href="route('prontuariosPaciente', [paciente.id])">
                                Voltar
                                </Link>
                                <Link v-if="prontuario !== undefined"
                                    class="inline-flex items-center ml-4 px-4 py-2 bg-rose-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:shadow-outline-rose transition ease-in-out duration-150"
                                    :href="route('deletarProntuario',[paciente.id, prontuario.id])">
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