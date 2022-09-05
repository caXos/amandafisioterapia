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
    pacientes: Array,
    atividades: Array,
    aparelhos: Array,
    fisios: Array
});

const localStatus = ref(props.status)
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
        for (let i=0; i<3; i++) {
            form.pacientes[i] = null
            form.atividades[i] = null
            form.aparelhos[i] = null
            form.fisios[i] = null
            $('#paciente'+(i+1)).val('')
            $('#atividade'+(i+1)).val('')
            $('#aparelho'+(i+1)).val('')
            habilitaAparelhos[i] = false
            $('#fisio'+(i+1)).val('')
        }
    } else {
        $('#dia').val(props.compromisso.dia);
        $('#hora').val(props.compromisso.hora.substring(0,5));
        for (let i=0; i<props.compromisso.atendimentos.length; i++) {
            form.pacientes[i] = props.compromisso.atendimentos[i].paciente_id
            form.atividades[i] = props.compromisso.atendimentos[i].atividade_id
            form.aparelhos[i] = props.compromisso.atendimentos[i].aparelho_id
            form.fisios[i] = props.compromisso.atendimentos[i].fisio_id
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
function trocaAtividade(evt) { //TODO: melhorar esse método
    switch(evt.srcElement.id) {
        case 'atividade1':
            this.habilitaAparelhos[0] = this.props.atividades[evt.target.selectedIndex - 1].usesAparatus;
            if (this.habilitaAparelhos[0] === true){
                $('#aparelho1').prop('required','required').prop('disabled', '')
            } else {
                $('#aparelho1').prop('disabled','disabled').removeProp('required')
            }
            break;
        case 'atividade2':
            this.habilitaAparelhos[1] = this.props.atividades[evt.target.selectedIndex - 1].usesAparatus;
            if (this.habilitaAparelhos[1] === true){
                $('#aparelho2').prop('required','required').prop('disabled', '')
            } else {
                $('#aparelho2').prop('disabled','disabled').removeProp('required')
            }
            break;
        case 'atividade3':
            this.habilitaAparelhos[2] = this.props.atividades[evt.target.selectedIndex - 1].usesAparatus;
            if (this.habilitaAparelhos[2] === true){
                $('#aparelho3').prop('required','required').prop('disabled', '')
            } else {
                $('#aparelho3').prop('disabled','disabled').removeProp('required')
            }
            break;
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
                            </div>
                            
                            <Fieldset>
                                    <template #rotulo>
                                        Atendimento 01
                                    </template>
                                    <template #conteudo>
                                        <div class="-mt-4">
                                            <BreezeLabel for="paciente1" value="Paciente" />
                                            <PacienteSelect id="paciente1" class="mt-1 block w-full" v-model="form.pacientes[0]"
                                                :pacientes="pacientes" required />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="atividade1" value="Atividade" />
                                            <AtividadeSelect id="atividade1" class="mt-1 block w-full" v-model="form.atividades[0]"
                                                :atividades="atividades" @change="trocaAtividade($event)"
                                            />
                                        </div>

                                        <div :class="{
                                            'mt-4': habilitaAparelhos[0] === true
                                            , 'mt-4 text-gray-400': habilitaAparelhos[0] === false
                                            }">
                                            <BreezeLabel for="aparelho1" value="Aparelho" />
                                            <AparelhoSelect id="aparelho1" class="mt-1 block w-full" v-model="form.aparelhos[0]"
                                                :aparelhos="aparelhos" disabled />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="fisio1" value="Fisioterapeuta" />
                                            <FisioSelect id="fisio1" class="mt-1 block w-full" v-model="form.fisios[0]" :fisios="fisios"
                                                required />
                                        </div>
                                    </template>
                            </Fieldset>

                            <Fieldset>
                                    <template #rotulo>
                                        Atendimento 02
                                    </template>
                                    <template #conteudo>
                                        <div class="-mt-4">
                                            <BreezeLabel for="paciente2" value="Paciente" />
                                            <PacienteSelect id="paciente2" class="mt-1 block w-full" v-model="form.pacientes[1]"
                                                :pacientes="pacientes" />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="atividade2" value="Atividade" />
                                            <AtividadeSelect id="atividade2" class="mt-1 block w-full" v-model="form.atividades[1]"
                                                :atividades="atividades" @change="trocaAtividade($event)" />
                                        </div>

                                        <div :class="{
                                            'mt-4': habilitaAparelhos[1] === true
                                            , 'mt-4 text-gray-400': habilitaAparelhos[1] === false
                                            }">
                                            <BreezeLabel for="aparelho2" value="Aparelho" />
                                            <AparelhoSelect id="aparelho2" class="mt-1 block w-full" v-model="form.aparelhos[1]"
                                                :aparelhos="aparelhos" disabled />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="fisio2" value="Fisioterapeuta" />
                                            <FisioSelect id="fisio2" class="mt-1 block w-full" v-model="form.fisios[1]" :fisios="fisios" />
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
                                            <PacienteSelect id="paciente3" class="mt-1 block w-full" v-model="form.pacientes[2]"
                                                :pacientes="pacientes" />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="atividade3" value="Atividade" />
                                            <AtividadeSelect id="atividade3" class="mt-1 block w-full" v-model="form.atividades[2]"
                                                :atividades="atividades" @change="trocaAtividade($event)" />
                                        </div>

                                        <div :class="{
                                            'mt-4': habilitaAparelhos[2] === true
                                            , 'mt-4 text-gray-400': habilitaAparelhos[2] === false
                                            }">
                                            <BreezeLabel for="aparelho3" value="Aparelho" />
                                            <AparelhoSelect id="aparelho3" class="mt-1 block w-full" v-model="form.aparelhos[2]"
                                                :aparelhos="aparelhos" disabled />
                                        </div>

                                        <div class="mt-4">
                                            <BreezeLabel for="fisio3" value="Fisioterapeuta" />
                                            <FisioSelect id="fisio3" class="mt-1 block w-full" v-model="form.fisios[2]" :fisios="fisios" />
                                        </div>
                                    </template>
                            </Fieldset>

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