<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeInputEdit from '@/Components/InputEdit.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import { onMounted, computed, ref } from 'vue';
import FisioSelect from '@/Components/FisioSelect.vue';
import PacienteSelect from '@/Components/PacienteSelect.vue';
import AtividadeSelect from '@/Components/AtividadeSelect.vue';
import AparelhoSelect from '@/Components/AparelhoSelect.vue';
import Fieldset from '@/Components/Fieldset.vue';

const props = defineProps({
    pacientes: Object,
    atividades: Object,
    aparelhos: Object,
    fisios: Object,
    status: String,

    compromisso: Object,
    fisio_id: Number,
    paciente_id: Number,
    atividade_id: Number,
    apareho_id: Number,
});

const form = useForm({
    dia: Date,
    hora: Date,

    paciente1: Number,
    atividade1: Number,
    aparelho1: Number,
    fisio1: Number,

    pacientes: Array,
    // paciente2: Number,
    // atividade2: Number,
    // aparelho2: Number,
    // fisio2: Number,

    // paciente3: Number,
    // atividade3: Number,
    // aparelho3: Number,
    // fisio3: Number,
});

const localStatus = ref(props.status)
const atividadeTeste = null
const habilitaAparelhos = ref([false, false, false])

const submit = () => {
    console.log(form);
    // localStatus.value = 'erro'
    // form.setError('atendimento2', 'Ha erros no formulario do atendimento 2')
    /*if (props.compromisso == null) {
        form.post(route('gravarCompromisso'), {
            onFinish: () => form.reset(),
        });
    } else {
        // form.post(route('editarAgenda'), {
        //     onFinish: () => form.reset(),
        // });
        alert('Editar Agenda!');
    }*/
};

onMounted(function () {
    if (props.compromisso == null || props.compromisso == undefined || props.compromisso == '' || props.compromisso.length == 0) {
        $('#dia').val('');
        $('#hora').val('');
    } else {
        $('#dia').val(props.compromisso.dia);
        $('#hora').val(props.compromisso.hora.substring(0,5));
        for (let i=0; i<props.compromisso.atendimentos.length; i++) {
            $('#paciente'+(i+1)).val(props.compromisso.atendimentos[i].paciente_id)
            $('#atividade'+(i+1)).val(props.compromisso.atendimentos[i].atividade_id)
            $('#aparelho'+(i+1)).val(props.compromisso.atendimentos[i].aparelho_id)
            habilitaAparelhos[i] = props.compromisso.atendimentos[i].aparelho_id
            $('#fisio'+(i+1)).val(props.compromisso.atendimentos[i].fisio_id)
        }
        
    }
});

</script>

<script>
function trocaAtividade(evt) {
    switch(evt.srcElement.id) {
        case 'atividade1':
            this.habilitaAparelhos[0] = this.props.atividades[evt.target.selectedIndex - 1].usesAparatus;
            if (this.habilitaAparelhos[0] === true){
                $('#aparelho1').prop('required','required').prop('disabled', '')
            } else {
                $('#aparelho1').prop('disabled','disabled').removeProp('required')
            }
            break;
        // case 'atividade2':
        //     this.habilitaAparelhos[1] = this.props.atividades[evt.target.selectedIndex - 1].usesAparatus;
        //     break;
        // case 'atividade3':
        //     this.habilitaAparelhos[2] = this.props.atividades[evt.target.selectedIndex - 1].usesAparatus;
        //     break;
        default:
            break;
    }
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
            <div v-if="localStatus != undefined" class="mb-4 font-medium text-sm text-green-600">
                {{ localStatus }}
                <div v-if="form.errors != ''" class="text-red-600">{{ form.errors.atendimento2 }}</div>
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <BreezeLabel for="dia" value="Data" />
                                <BreezeInput id="dia" type="date" class="mt-1 block w-full" v-model="form.dia" required autofocus />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="hora" value="Hora" />
                                <BreezeInput id="hora" type="time" class="mt-1 block w-full"
                                    v-model="form.hora" required />
                                <!-- <BreezeInputEdit v-else id="hora" type="time" class="mt-1 block w-full"
                                    v-model="form.hora" :valorParaEditar="compromisso.hora" :container="'hora'" required
                                    autofocus /> -->
                            </div>
                            
                            <Fieldset>
                                    <template #rotulo>
                                        Atendimento 01
                                    </template>
                                    <template #conteudo>
                                        <div class="-mt-4">
                                            <BreezeLabel for="paciente1" value="Paciente" />
                                            <PacienteSelect id="paciente1" class="mt-1 block w-full" v-model="form.paciente1"
                                                :pacientes="pacientes" :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length === 0
                                                    , 'compromisso.atendimentos[0].paciente_id': compromisso != undefined && compromisso.atendimentos.length === 1
                                                }" required />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="atividade1" value="Atividade" />
                                            <AtividadeSelect id="atividade1" class="mt-1 block w-full" v-model="form.atividade1"
                                                :atividades="atividades" @change="trocaAtividade($event)"
                                                :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length === 0
                                                    , 'compromisso.atendimentos[0].atividade_id': compromisso != undefined && compromisso.atendimentos.length === 1
                                                }" />
                                        </div>

                                        <div :class="{
                                            'mt-4': habilitaAparelhos[0] === true
                                            , 'mt-4 text-gray-400': habilitaAparelhos[0] === false
                                            }">
                                            <BreezeLabel for="aparelho1" value="Aparelho" />
                                            <AparelhoSelect id="aparelho1" class="mt-1 block w-full" v-model="form.aparelho1"
                                                :aparelhos="aparelhos" :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length === 0
                                                    , 'compromisso.atendimentos[0].aparelho_id': compromisso != undefined && compromisso.atendimentos.length === 1
                                                }" disabled
                                                />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="fisio1" value="Fisioterapeuta" />
                                            <FisioSelect id="fisio1" class="mt-1 block w-full" v-model="form.fisio1" :fisios="fisios"
                                                :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length === 0
                                                    , 'compromisso.atendimentos[0].fisio_id': compromisso != undefined && compromisso.atendimentos.length === 1
                                                }" required />
                                        </div>
                                    </template>
                            </Fieldset>

                            <!-- <Fieldset>
                                    <template #rotulo>
                                        Atendimento 02
                                    </template>
                                    <template #conteudo>
                                        <div class="-mt-4">
                                            <BreezeLabel for="paciente2" value="Paciente" />
                                            <PacienteSelect id="paciente2" class="mt-1 block w-full" v-model="form.paciente2"
                                                :pacientes="pacientes" :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length < 2
                                                    , 'compromisso.atendimentos[1].paciente_id': compromisso != undefined && compromisso.atendimentos.length >= 2
                                                }" />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="atividade2" value="Atividade" />
                                            <AtividadeSelect id="atividade2" class="mt-1 block w-full" v-model="form.atividade2"
                                                :atividades="atividades" @change="trocaAtividade($event)"
                                                :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length < 2
                                                    , 'compromisso.atendimentos[1].atividade_id': compromisso != undefined && compromisso.atendimentos.length >= 2
                                                }" />
                                        </div>

                                        <div v-if="atividadeTeste" class="mt-4">
                                            <BreezeLabel for="aparelho2" value="Aparelho" />
                                            <AparelhoSelect id="aparelho2" class="mt-1 block w-full" v-model="form.aparelho2"
                                                :aparelhos="aparelhos" :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length < 2
                                                    , 'compromisso.atendimentos[1].aparelho_id': compromisso != undefined && compromisso.atendimentos.length >= 2
                                                }"  />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="fisio2" value="Fisioterapeuta" />
                                            <FisioSelect id="fisio2" class="mt-1 block w-full" v-model="form.fisio2" :fisios="fisios"
                                            :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length < 2
                                                    , 'compromisso.atendimentos[1].fisio_id': compromisso != undefined && compromisso.atendimentos.length >= 2
                                                }" />
                                        </div>
                                    </template>
                            </Fieldset>

                            <Fieldset>
                                    <template #rotulo>
                                        Atendimento 03
                                    </template>
                                    <template #conteudo>
                                        <div class="-mt-4">
                                            <BreezeLabel for="paciente3" value="Paciente" />
                                            <PacienteSelect id="paciente3" class="mt-1 block w-full" v-model="form.paciente3"
                                                :pacientes="pacientes" :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length < 3
                                                    , 'compromisso.atendimentos[2].paciente_id': compromisso != undefined && compromisso.atendimentos.length >= 3
                                                }" />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="atividade3" value="Atividade" />
                                            <AtividadeSelect id="atividade3" class="mt-1 block w-full" v-model="form.atividade3"
                                                :atividades="atividades" @change="trocaAtividade($event)"
                                                :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length < 3
                                                    , 'compromisso.atendimentos[2].atividade_id': compromisso != undefined && compromisso.atendimentos.length >= 3
                                                }" />
                                        </div>

                                        <div v-if="atividadeTeste" class="mt-4">
                                            <BreezeLabel for="aparelho3" value="Aparelho" />
                                            <AparelhoSelect id="aparelho3" class="mt-1 block w-full" v-model="form.aparelho3"
                                                :aparelhos="aparelhos" :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length < 2
                                                    , 'compromisso.atendimentos[2].aparelho_id': compromisso != undefined && compromisso.atendimentos.length >= 3
                                                }" />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="fisio3" value="Fisioterapeuta" />
                                            <FisioSelect id="fisio3" class="mt-1 block w-full" v-model="form.fisio3" :fisios="fisios"
                                            :selectedIndex="{
                                                    '': compromisso !== undefined && compromisso.atendimentos.length < 3
                                                    , 'compromisso.atendimentos[2].fisio_id': compromisso != undefined && compromisso.atendimentos.length >= 3
                                                }" />
                                        </div>
                                    </template>
                            </Fieldset> -->

                            <div class="flex items-center justify-end mt-4">
                                <Link class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:shadow-outline-slate transition ease-in-out duration-150" :href="route('agenda')">
                                    Voltar
                                </Link>
                                <Link v-if="compromisso !== undefined" class="inline-flex items-center ml-4 px-4 py-2 bg-rose-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:shadow-outline-rose transition ease-in-out duration-150" :href="route('deletarCompromisso', [props.compromisso.id])">
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