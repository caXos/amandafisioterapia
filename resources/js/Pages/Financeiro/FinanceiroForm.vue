<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeInputEdit from '@/Components/InputEdit.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { onBeforeMount, onMounted, computed, ref } from 'vue';
import FinanceiroTipoSelect from '@/Components/FinanceiroTipoSelect.vue';

const props = defineProps({
    // financeiro: Object,
    date: Date,
    time: Date
});

const form = useForm({
    date: Date,
    time: Date,
    description: String,
    detail: String,
    type: Number,
    value: Number,
});

const submit = () => {
    // console.log(form);
    if (props.agenda == null) {
        form.post(route('gravarfinanceiro'), {
            onFinish: () => form.reset(),
        });
    } else {
        // form.post(route('editarAgenda'), {
        //     onFinish: () => form.reset(),
        // });
        alert('Editar Financeiro!');
    }

};

// onBeforeMount(() => {
//     let diaHoje = new Date().toLocaleDateString();
//     let horaAgora = new Date().toLocaleTimeString();
//     $('#date').val(diaHoje);
//     $('#time').val(horaAgora);
// });

onMounted(() => {
    console.log("props", props);
    var novaData = new Date();
    // var diaHoje = novaData.getDate().toString()+"/"+novaData.toISOString().getMonth()+"/"+novaData.getFullYear().toString();
    var diaHoje = novaData.toLocaleDateString();
    diaHoje = diaHoje.toString();
    var horaAgora = novaData.getHours().toString()+":"+novaData.getMinutes().toString();
    console.log(diaHoje, horaAgora);
    $('#date').val(diaHoje);
    $('#time').val(horaAgora);
});
</script>

<script>

</script>

<template>

    <Head title="Financeiro" />
    <BreezeValidationErrors class="mb-4" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-sky-800 leading-tight">
                Financeiro - Criar lançamento
            </h2>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <BreezeLabel for="date" value="Data" />
                                <BreezeInput v-if="financeiro == null" id="date" type="date" class="mt-1 block w-full"
                                    v-model="form.date" required autofocus value=props.date />
                                <!-- <BreezeInputEdit v-else id="date" type="date" class="mt-1 block w-full"
                                    v-model="form.date" :valorParaEditar="agenda.date" :container="'date'" required
                                    autofocus /> -->
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="time" value="Hora" />
                                <BreezeInput v-if="financeiro == null" id="time" type="time" class="mt-1 block w-full"
                                    v-model="form.time" required />
                                <!-- <BreezeInputEdit v-else id="time" type="time" class="mt-1 block w-full"
                                    v-model="form.time" :valorParaEditar="agenda.time" :container="'time'" required
                                    autofocus /> -->
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="description" value="Descrição" />
                                <BreezeInput id="description" class="mt-1 block w-full" type="text" v-model="form.description" required />
                                <!-- <BreezeInputEdit v-else id="description" class="mt-1 block w-full" v-model="form.description" :valorParaEditar="financeiro.description" :container="'description'" required /> -->
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="detail" value="Detalhe" />
                                <BreezeInput v-if="financeiro == null" id="detail" type="text" class="mt-1 block w-full" v-model="form.detail" />
                                <!-- <BreezeInputEdit v-else id="detail" class="mt-1 block w-full" v-model="form.detail" :valorParaEditar="financeiro.detail" :container="'detail'" /> -->
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="type" value="Tipo" />
                                <FinanceiroTipoSelect v-if="financeiro == null" id="type" class="mt-1 block w-full"
                                    v-model="form.type" required />
                                <!-- <FinanceiroTipoSelect v-else  id="type" class="mt-1 block w-full" v-model="form.type" :valorParaEditar="financeiro.detail" :container="'detail'" required /> -->
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="value" value="Valor" />
                                <BreezeInput v-if="financeiro == null" id="value" type="number" step="0.01" class="mt-1 block w-full" v-model="form.value" required />
                                <!-- <BreezeInputEdit v-else id="value" class="mt-1 block w-full" v-model="form.value" :valorParaEditar="financeiro.value" :container="'value'" required /> -->
                            </div>

                            <div class="flex items-center justify-end mt-4">
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