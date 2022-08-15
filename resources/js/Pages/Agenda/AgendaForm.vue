<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeInputEdit from '@/Components/InputEdit.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { onMounted, computed, ref } from 'vue';
import FisioSelect from '@/Components/FisioSelect.vue';
import PacienteSelect from '@/Components/PacienteSelect.vue';
import AtividadeSelect from '@/Components/AtividadeSelect.vue';
import AparelhoSelect from '@/Components/AparelhoSelect.vue';

const props = defineProps({
    pacientes: Object,
    atividades: Object,
    aparelhos: Object,
    fisios: Object,
    status: String,

    agenda: Object,
    fisio_id: Number,
    paciente_id: Number,
    atividade_id: Number,
    apareho_id: Number,
});

const form = useForm({
    date: Date,
    time: Date,
    paciente: Number,
    atividade: Number,
    aparelho: Number,
    fisio: Number,
});

const submit = () => {
    // console.log(form);
    if (props.agenda == null) {
        form.post(route('gravarAgenda'), {
            onFinish: () => form.reset(),
        });
    } else {
        // form.post(route('editarAgenda'), {
        //     onFinish: () => form.reset(),
        // });
        alert('Editar Agenda!');
    }

};

// onMounted(() => {
//     console.log(props.paciente_id);
// });
const atividadeTeste = null;
</script>

<script>
function trocaAtividade(evt) {
    this.atividadeTeste = this.props.atividades[evt.target.selectedIndex - 1].usesAparatus;
}

function trocaAtividade_bckp(evt) {
    this.atividadeTeste = evt.target.selectedIndex;
}
</script>

<template>

    <Head title="Agenda" />
    <BreezeValidationErrors class="mb-4" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Agenda - Criar/editar compromisso
            </p>
            <!-- <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div> -->
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <BreezeLabel for="date" value="Data" />
                                <BreezeInput v-if="agenda == null" id="date" type="date" class="mt-1 block w-full"
                                    v-model="form.date" required autofocus />
                                <BreezeInputEdit v-else id="date" type="date" class="mt-1 block w-full"
                                    v-model="form.date" :valorParaEditar="agenda.date" :container="'date'" required
                                    autofocus />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="time" value="Hora" />
                                <BreezeInput v-if="agenda == null" id="time" type="time" class="mt-1 block w-full"
                                    v-model="form.time" required />
                                <BreezeInputEdit v-else id="time" type="time" class="mt-1 block w-full"
                                    v-model="form.time" :valorParaEditar="agenda.time" :container="'time'" required
                                    autofocus />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="paciente" value="Paciente" />
                                <PacienteSelect id="paciente" class="mt-1 block w-full" v-model="form.paciente"
                                    :pacientes="pacientes" :selectedIndex="paciente_id" required />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="atividade" value="Atividade" />
                                <AtividadeSelect id="atividade" class="mt-1 block w-full" v-model="form.atividade"
                                    :atividades="atividades" required @change="trocaAtividade($event)"
                                    :selectedIndex="atividade_id" />
                            </div>

                            <div v-if="atividadeTeste" class="mt-4">
                                <BreezeLabel for="aparelho" value="Aparelho" />
                                <AparelhoSelect id="aparelho" class="mt-1 block w-full" v-model="form.aparelho"
                                    :aparelhos="aparelhos" :selectedIndex="aparelho_id" required />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="fisio" value="Fisioterapeuta" />
                                <FisioSelect id="fisio" class="mt-1 block w-full" v-model="form.fisio" :fisios="fisios"
                                    :selectedIndex="fisio_id" required />
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