<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import { onMounted, onBeforeUpdate, computed, ref, nextTick } from 'vue';

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
  outrosCompromissos: Object,
  planos_pacientes: Object
});

const form = useForm({
  dia: Date,
  hora: Date,
  pacientes: Number,
  atividades: String,
  aparelhos: Array,
  fisios: Array,
  vagas: Number,
  quantidade: Number,

  compromisso_id: Number
});

const status = ref(props.status)
const compromisso = ref(props.compromisso)
const habilitaAparelhos = ref([false, false, false])
const vagas = ref(0)

const submit = () => {
  form.dia = $('#dia').val();
  form.hora = $('#hora').val();
  form.vagas = vagas
  let pacientesArray = []
  let atividadesArray = []
  let aparelhosArray = []
  let fisiosArray = []
  for (let i = 0; i < form.vagas; i++) {
    pacientesArray.push($('#paciente-' + i).val())
    atividadesArray.push($('#atividade-' + i).val())
    aparelhosArray.push($('#aparelho-' + i).val())
    fisiosArray.push($('#fisio-' + i).val())
  }
  form.pacientes = pacientesArray
  form.atividades = atividadesArray
  form.aparelhos = aparelhosArray
  form.fisios = fisiosArray
  // if(validaFormulario()) {
  //     //formulário ok, segue com o gravar compromisso ou editar compromisso
  // }
  // status.value = 'erro'
  // form.setError('atendimento2', 'Ha erros no formulario do atendimento 2')
  if (props.compromisso == null) {
    form.post(route('gravarCompromisso'), {
      onFinish: () => form.reset(),
    });
  } else {
    form.post(route('atualizarCompromisso'), {
      onFinish: () => form.reset(),
    });
  }
};

onMounted(function () {
  if (props.compromisso == null || props.compromisso == undefined || props.compromisso == '' || props.compromisso.length == 0) {
    vagas.value = 3
    // $('#dia').val('').prop('min', new Date()); //Verificar se new Date é suficiente
    // $('#hora').val('').prop('min', new Date()); //Verificar se new Date é suficiente
    // let diaDeHoje = new Date().toISOString().substring(0,10)
    $('#dia').val('')//.prop('min', diaDeHoje)
    $('#hora').val('')
    $('#vagas').val(vagas.value)
    for (let i = 0; i < vagas.value; i++) {
      form.pacientes[i] = null
      form.atividades[i] = null
      form.aparelhos[i] = null
      form.fisios[i] = null
      $('#paciente-' + (i + 1)).val('')
      $('#atividade-' + (i + 1)).val('')
      $('#aparelho-' + (i + 1)).val('')
      habilitaAparelhos[i] = false
      $('#fisio-' + (i + 1)).val('')
    }
  } else {
    vagas.value = props.compromisso.vagas
    let diaDeHoje = new Date().toISOString().substring(0, 10)
    $('#dia').val(props.compromisso.dia)//.prop('min', diaDeHoje)
    $('#hora').val(props.compromisso.hora.substring(0, 5))
    $('#vagas').val(props.compromisso.vagas)
    form.compromisso_id = props.compromisso.id
    nextTick(function () {
      for (let i = 0; i < props.compromisso.atendimentos.length; i++) {
        console.log(props.compromisso.atendimentos[i])
        form.pacientes[i] = props.compromisso.atendimentos[i].paciente_id
        form.atividades[i] = props.compromisso.atendimentos[i].atividade_id
        form.aparelhos[i] = props.compromisso.atendimentos[i].aparelho_id
        form.fisios[i] = props.compromisso.atendimentos[i].fisio_id
        $('#paciente-' + i).val(parseInt(props.compromisso.atendimentos[i].paciente_id))
        $('#atividade-' + i).val(props.compromisso.atendimentos[i].atividade_id)
        if (props.compromisso.atendimentos[i].aparelho_id !== null && props.compromisso.atendimentos[i].aparelho_id !== undefined && props.compromisso.atendimentos[i].aparelho_id !== '' && props.compromisso.atendimentos[i].aparelho_id >= 0) {
          $('#aparelho-' + i).val(props.compromisso.atendimentos[i].aparelho_id)
          habilitaAparelhos._rawValue[i] = true
          $('#aparelho-' + i).prop('disabled', '').prop('required', 'required').removeClass('text-gray-400')
        }
        $('#fisio-' + i).val(props.compromisso.atendimentos[i].fisio_id)
      }
    })
  }
});

// function validaFormulario() {
//     //Os inputs dia e hora já são validados pelo atributo required, mas é necessário validar seus valores
//     let diaDeHoje = new Date().getDate()
//     let diaPreenchido = new Date(form.dia)
//     if (diaPreenchido < diaDeHoje) {
//         //Erro: não é permitido marcar compromissos passados
//         status.value = 'erro'
//         form.setError('Erro01', 'Não é permitido marcar compromissos em dias passados')
//         return false
//     } else {
//         let horaAgora = new Date().getTime()
//         let horaPreenchida = new Date(form.hora)
//         if (horaPreenchida < horaAgora) {
//             //Erro: é permitido marcar compromissos no mesmo dia, mas não em hora anterior
//             status.value = 'erro'
//             form.setError('Erro02', 'Não é permitido marcar compromissos no dia atual com hora passada')
//         } else {
//             //Tudo ok com dia e hora, agora é a vez de consultar o atendimento 01
//             if ((form.pacientes[0] === null || form.pacientes[0] === undefined || form.pacientes[0] === '' || form.pacientes[0] === 0)
//                 && (form.atividades[0] === null || form.atividades[0] === undefined || form.atividades[0] === '' || form.atividades[0] === 0)
//                 && (form.aparelhos[0] === null || form.aparelhos[0] === undefined || form.aparelhos[0] === '' || form.aparelhos[0] === 0)
//                 && (form.fisios[0] === null || form.fisios[0] === undefined || form.fisios[0] === '' || form.fisios[0] === 0)
//             ) {
//                 //Todos os campos do atendimento 01 estão 'em branco', então, em princípio, tudo certo...
//             } else {
//                 //Um dos campos do atendimento 01 foi preenchido, mas pelo menos um ficou em branco, então é erro
//                 status.value = 'erro'
//                 if ((form.pacientes[0] === null || form.pacientes[0] === undefined || form.pacientes[0] === '' || form.pacientes[0] === 0)) form.setError('Erro03', 'É necessário preencher o nome do paciente do atendimento 01')
//                 if ((form.atividades[0] === null || form.atividades[0] === undefined || form.atividades[0] === '' || form.atividades[0] === 0)) form.setError('Erro04', 'É necessário preencher a atividade do atendimento 01')
//                 if (this.habilitaAparelhos[0] === true && (form.aparelhos[0] === null || form.aparelhos[0] === undefined || form.aparelhos[0] === '' || form.aparelhos[0] === 0)) form.setError('Erro05', 'É necessário indicar o aparelho da atividade do atendimento 01')
//                 if ((form.fisios[0] === null || form.fisios[0] === undefined || form.fisios[0] === '' || form.fisios[0] === 0)) form.setError('Erro06', 'É necessário preencher o(a) fisioterapeuta do atendimento 01')
//                 return false
//             }
//         }
//     }
// }

function alteraQtdevagas(evt) {
  vagas.value = parseInt(evt.target.value)
}

function buscaCompromisso() {
  if ($('#dia').val() !== '' && $('#hora').val() !== '') {
    let verificaDia = $('#dia').val()
    let verificaHora = $('#hora').val()
    verificaHora = verificaHora.concat(":00")
    for (let i = 0; i < props.outrosCompromissos.length; i++) {
      if (props.outrosCompromissos[i].dia === verificaDia && props.outrosCompromissos[i].hora === verificaHora) {
        console.log('achei um compromisso j marcado com esse dia e hora')
        compromisso.value = props.outrosCompromissos[i]
        vagas.value = props.outrosCompromissos[i].vagas
        $('#vagas').val(props.outrosCompromissos[i].vagas)
        for (let j = 0; j < props.outrosCompromissos[i].vagas; j++) {
          $('#paciente-' + j).val(parseInt(props.outrosCompromissos[i].atendimentos[j].paciente_id))
          $('#atividade-' + j).val(props.outrosCompromissos[i].atendimentos[j].atividade_id)
          if (props.outrosCompromissos[i].atendimentos[j].aparelho_id !== null && props.outrosCompromissos[i].atendimentos[j].aparelho_id !== undefined && props.outrosCompromissos[i].atendimentos[j].aparelho_id !== '' && props.outrosCompromissos[i].atendimentos[j].aparelho_id >= 0) {
            $('#aparelho-' + j).val(props.outrosCompromissos[i].atendimentos[j].aparelho_id)
            habilitaAparelhos._rawValue[j] = true
            $('#aparelho-' + j).prop('disabled', '').prop('required', 'required').removeClass('text-gray-400')
          }
          $('#fisio-' + i).val(props.outrosCompromissos[i].atendimentos[j].fisio_id)
        }
      }
    }
  }
}

function verificaAtividadePaciente(event) {
  let indice = event.target.id.substring(9)
  $('#atividade-' + indice).val(props.planos_pacientes[parseInt(event.target.value)].atividades)
  habilitaAparelhos[indice] = props.atividades[$('#atividade-' + indice).val()].usesAparatus;
  habilitaAparelhos[indice] === true ? $('#aparelho-' + indice).prop('required', 'required').prop('disabled', '') : $('#aparelho-' + indice).prop('disabled', 'disabled').removeProp('required').val(1)
}


</script>

<script>
function trocaAtividade(evt) { //TODO: melhorar esse método
  // console.log(evt)
  let indice = evt.target.id.substring(10)
  console.log(this.habilitaAparelhos)
  console.log(this.habilitaAparelhos[indice])
  this.habilitaAparelhos[indice] = this.props.atividades[evt.target.selectedIndex - 1].usesAparatus;
  // this.habilitaAparelhos[indice] === true ? $('#aparelho-' + indice).prop('required', 'required').prop('disabled', '') : $('#aparelho-' + indice).prop('disabled', 'disabled').removeProp('required').val(1)
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
      <div v-if="status != undefined" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
      <div v-if="status === 'erro'" class="mb-4 font-medium text-sm text-red-600">
        <div v-if="form.errors != ''">{{ form.errors.atendimento2 }}</div>
      </div>
    </template>

    <div class="py-2">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="submit">
              <div class="mb-1 lg:grid lg:grid-cols-3">
                <div class="lg:mr-1">
                  <BreezeLabel for="dia" value="Data" />
                  <BreezeInput id="dia" type="date" class="mt-1 block w-full" v-model="form.dia" required autofocus
                    @blur="buscaCompromisso" />
                </div>

                <div class="mt-4 lg:mx-1 lg:mt-0">
                  <BreezeLabel for="hora" value="Hora" />
                  <BreezeInput id="hora" type="time" class="mt-1 block w-full" v-model="form.hora" required
                    @blur="buscaCompromisso" />
                </div>
                <div class="mt-4 lg:ml-1 lg:mt-0">
                  <BreezeLabel for="vagas" value="Vagas" />
                  <BreezeInput id="vagas" type="number" step="1" min="0" max="15" class="mt-1 block w-full"
                    v-model="form.vagas" @change="alteraQtdevagas" required />
                </div>
              </div>

              <Fieldset v-for="(vaga,index) in vagas" :key="index">
                <template #rotulo>
                  Atendimento {{index+1}}
                </template>
                <template #conteudo>
                  <div class="-mt-4">
                    <BreezeLabel :for="`paciente-${index}`" value="Paciente" />
                    <PacienteSelect :id="`paciente-${index}`" class="mt-1 block w-full" v-model="form.pacientes[index]"
                      :pacientes="pacientes" required @change="verificaAtividadePaciente($event)" />
                  </div>

                  <div class="mt-4">
                    <BreezeLabel :for="`atividade-${index}`" value="Atividade" />
                    <AtividadeSelect :id="`atividade-${index}`" class="mt-1 block w-full"
                      v-model="form.atividades[index]" :atividades="atividades" @change="trocaAtividade($event)"
                      required />
                  </div>

                  <div class="mt-4" :class="{'text-gray-400': habilitaAparelhos[index] !== true}">
                    <BreezeLabel :for="`aparelho-${index}`" value="Aparelho" />
                    <AparelhoSelect :id="`aparelho-${index}`" class="mt-1 block w-full" v-model="form.aparelhos[index]"
                      :aparelhos="aparelhos" :disabled="habilitaAparelhos[index] !== true"
                      :required="habilitaAparelhos[index] === true" />
                  </div>

                  <div class="mt-4">
                    <BreezeLabel :for="`fisio-${index}`" value="Fisioterapeuta" />
                    <FisioSelect :id="`fisio-${index}`" class="mt-1 block w-full" v-model="form.fisios[index]"
                      :fisios="fisios" required />
                  </div>

                  <div v-if="props.compromisso != undefined" class="">
                    <!-- <div class="lg:grid lg:grid-cols-2"> -->
                    <!-- Receber o valor abaixo da seguinte forma:
                                        com o id do plano, tem como saber o total de atendimentos do plano, 
                                        quantos atendimentos ainda há, e a frequencia.
                                        dividir a quantidade que ainda há pela frequencia, e sugerir o valor, arredondado pra baixo.
                                        isso seria uma sugestão para dividir os atendimentos de forma igual -->
                    <!-- Verificar real necessidade de colocar esse campo de quantidade, pois, em teoria,
                                        esta tela deve ser para criar compromissos unitários. Para gerar atendimentos em lote,
                                        deve-se usar a tela de criação ou edição de paciente. Mas isso pode mudar... -->
                    <!-- <div class="mt-4">
                                            <BreezeLabel :for="`quantidade-${index}`" value="Quantidade" />
                                            <BreezeInput id="quantidade" type="number" step="1" min="1" max="99"
                                                class="mt-1 block w-full" v-model="form.quantidade" required value="1" />
                                        </div> -->

                    <div class="flex items-center justify-center mt-2 mb-1">
                      <span
                        class="inline-flex items-center mx-4 my-2 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                        :title="`Notificar paciente ${index+1}`"
                        @click="$emit('notificarCompromissoToda', compromisso)">notifications</span>
                      <span
                        class="inline-flex items-center mx-4 my-2 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                        :title="`Marcar atendimento ${index+1} como completado`"
                        @click="$emit('completarCompromissoToda',compromisso)">done</span>
                      <span
                        class="inline-flex items-center mx-4 my-2 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                        :title="`Marcar atendimento ${index+1} com falta`"
                        @click="$emit('faltarCompromissoTodo', compromisso)">event_busy</span>
                      <span
                        class="inline-flex items-center mx-4 my-2 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                        :title="`Deletar atendimento ${index+1} sem completar`"
                        @click="$emit('deletarCompromissoTodo', compromisso)">delete</span>
                      <span
                        class="inline-flex items-center mx-4 my-2 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                        :title="`Agendar retorno para paciente ${index+1}`"
                        @click="$emit('retornarCompromissoTodo', compromisso)">forward</span>
                    </div>
                  </div>
                </template>
              </Fieldset>

              <div class="flex items-center justify-end mt-4">
                <Link
                  class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:shadow-outline-slate transition ease-in-out duration-150"
                  :href="route('agenda')">
                Voltar
                </Link>

                <Link v-if="compromisso != null"
                  class="inline-flex items-center ml-4 px-4 py-2 bg-rose-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:shadow-outline-rose transition ease-in-out duration-150"
                  :href="route('deletarCompromisso', [compromisso.id])">
                Remover
                </Link>

                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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