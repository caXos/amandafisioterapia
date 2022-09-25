<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import { onMounted, computed, ref } from 'vue';
import FisioSelect from '@/Components/FisioSelect.vue';
import PlanoSelect from '@/Components/PlanoSelect.vue';
import Fieldset from '@/Components/Fieldset.vue';

const props = defineProps({
  status: String,
  planos: Object,
  fisios: Object,
  paciente: Object,
  plano: Object
});

const form = useForm({
  nome: String,
  plano: Number,
  inicio: Date,
  fim: Date,
  fisio: Number,
  observacao: String,
  telefone: String,
  nascimento: Date,
  dias: String,
  horarios: String,
});

const frequencia = ref(0)
const diasDaSemana = ref([
  'Segundas-feiras',
  'Terças-feiras',
  'Quartas-feiras',
  'Quintas-feiras',
  'Sextas-feiras'
])

onMounted(function () {
  if (props.paciente == null || props.paciente == undefined || props.paciente == '' || props.paciente.length == 0) {
    $('#nome').val('');
    $('#plano').val(0);
    $('#inicio').val('');
    $('#fim').val('');
    $('#fisio').val(0);
    $('#observacao').val('');
    $('#telefone').val('');
    $('#nascimento').val('');
  } else {
    $('#nome').val(props.paciente.nome);
    $('#plano').val(props.paciente.plano_id);
    $('#inicio').val(props.plano.inicio);
    $('#fim').val(props.plano.fim);
    $('#fisio').val(props.paciente.fisio_id);
    $('#observacao').val(props.paciente.observacao);
    $('#telefone').val(props.paciente.telefone);
    $('#nascimento').val(props.paciente.nascimento);
  }
});
const submit = () => {
  form.nome = $('#nome').val();
  form.plano = parseInt($('#plano').val());
  form.inicio = $('#inicio').val();
  form.fim = $('#fim').val();
  form.fisio = $('#fisio').val();
  form.observacao = $('#observacao').val();
  form.telefone = $('#telefone').val();
  form.nascimento = $('#nascimento').val();
  let diasArray = []
  let horariosArray = []

  for (let i = 0; i < frequencia.value; i++) {
    diasArray.push($('#dias-' + i).val())
    horariosArray.push($('#horarios-' + i).val())
  }

  form.dias = diasArray
  form.horarios = horariosArray;

  if (props.paciente == null || props.paciente == undefined || props.paciente == '' || props.paciente.length == 0) {
    form.post(route('gravarPaciente'), {
      onFinish: () => {
        $('#plano').val('0')
        $('#fisio').val('0')
        // form.reset()
      }
    });
  } else {
    form.post(route('editarPaciente', [props.paciente.id]), {
      onFinish: () => {
        $('#plano').val('0')
        $('#fisio').val('0')
        // form.reset()
      }
    });
  }
};

</script>

<script>
function habilitaDataInicio() {
  $('#inicio').removeAttr('disabled');
  let freq = $('#plano>option:selected').attr('frequencia')
  freq = freq.substring(0, 1)
  if (freq === 'ú') this.frequencia = 1
  else this.frequencia = parseInt(freq)
  $('#atendimentos_totais').val($('#plano>option:selected').attr('qtdAtendimentos'))
  $('#atendimentos_para_criar').prop('max', $('#plano>option:selected').attr('qtdAtendimentos')).val($('#plano>option:selected').attr('qtdAtendimentos'))
}
function calculaFim(evt) {
  $('#fim').removeAttr('disabled');
  var tempoPhp = $('#plano>option:selected').attr('tempoPHP');
  var dataInicio = $('#inicio').val();
  var dataFim = null;
  dataFim = new Date(dataInicio).setMonth(new Date(dataInicio).getMonth() + (tempoPhp / 30))
  dataFim = new Date(dataFim).toISOString()
  $('#fim').val(dataFim.substring(0, 10))
}
function mascaraTelefone(evt) {
  // console.log('mascaraTelefone', evt);
  // $('#telefone').mask('(99) 999-999-999');
}

function desabilitaDias(evt) {
  console.log(evt.target.selectedIndex)
  if (evt.target.id === 'dias-0') {
    // $('#dias-1>option').removeAttr('disabled')
    $('#dias-1>option[value="' + (evt.target.selectedIndex + 1) + '"]').prop('disabled', 'disabled')
  }
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

              <div class="mt-4">
                <BreezeLabel for="observacao" value="Observação" />
                <BreezeInput id="observacao" type="text" class="mt-1 block w-full" v-model="form.observacao"
                  placeholder="Alguma informação relevante" />
              </div>

              <div class="lg:grid lg:grid-cols-2">
                <div class="mt-4 lg:pr-2">
                  <BreezeLabel for="telefone" value="Telefone" />
                  <!-- <BreezeInput id="telefone" class="mt-1 block w-full" type="text" v-model="form.telefone"
                                        pattern="(99) 999-999-999" data-mask="(99) 999-999-999" placeholder='(99) 999-999-999' 
                                        @change="mascaraTelefone($event)" required /> -->
                  <BreezeInput id="telefone" class="mt-1 block w-full" type="text" v-model="form.telefone"
                    placeholder='(99) 999-999-999' @change="mascaraTelefone($event)" required />
                </div>

                <div class="mt-4 lg:pl-2">
                  <BreezeLabel for="nascimento" value="Data Nascimento" />
                  <BreezeInput id="nascimento" type="date" class="mt-1 block w-full" v-model="form.nascimento"
                    required />
                </div>
              </div>
              <div class="lg:grid lg:grid-cols-2">
                <div class="mt-4 lg:pr-2">
                  <BreezeLabel for="plano" value="Plano" />
                  <PlanoSelect id="plano" class="mt-1 block w-full" v-model="form.plano" :planos="planos"
                    :selectedIndex="planos.id" required @change="habilitaDataInicio()" />
                </div>
                <div class="mt-4 lg:pl-2">
                  <BreezeLabel for="fisio" value="Fisioterapeuta" />
                  <FisioSelect id="fisio" class="mt-1 block w-full" v-model="form.fisio" :fisios="fisios"
                    :selectedIndex="fisio_id" required />
                </div>
              </div>
              <div class="lg:grid lg:grid-cols-2">
                <div class="mt-4 lg:pr-2">
                  <BreezeLabel for="inicio" value="Data Início" />
                  <BreezeInput id="inicio" type="date" class="mt-1 block w-full" v-model="form.inicio"
                    @change="calculaFim($event)" required disabled />
                </div>
                <div class="mt-4 lg:pl-2">
                  <BreezeLabel for="fim" value="Data Fim" />
                  <BreezeInput id="fim" type="date" class="mt-1 block w-full" v-model="form.fim" disabled />
                </div>
              </div>

              <div class="lg:grid lg:grid-cols-2">
                <div class="mt-4 lg:pr-2">
                  <BreezeLabel for="atendimentos_totais" value="Quantidade de atendimentos do plano" />
                  <BreezeInput id="atendimentos_totais" type="number" class="mt-1 block w-full" disabled />
                </div>
                <div class="mt-4 lg:pl-2">
                  <BreezeLabel for="atendimentos_para_criar" value="Quantidade de atendimentos para gerar" />
                  <BreezeInput id="atendimentos_para_criar" type="number" step="1" min="0" class="mt-1 block w-full"
                    required />
                </div>
              </div>

              <Fieldset v-if="frequencia > 0">
                <template #rotulo>
                  Dia<span v-if="frequencia >= 2">s</span> e Horário<span v-if="frequencia >= 2">s</span>
                </template>
                <template #conteudo>
                  <div class="lg:grid" :class="`lg:grid-cols-${frequencia}`">
                    <div v-for="index in frequencia" class="lg:grid lg:grid-cols-2">
                      <div class="mt-4 lg:mt-0 lg:px-2" :class="{'mt-0':index==1}">
                        <BreezeLabel :for="`dias-${index-1}`" value="Dia" />
                        <select
                          class="border-cyan-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full"
                          :id="`dias-${index-1}`" @change="desabilitaDias($event)">
                          <option v-for="(diaDaSemana, index) in diasDaSemana" :key="index" :value="index+1">
                            {{diaDaSemana}}
                          </option>
                        </select>
                      </div>
                      <div class="lg:mt-0 lg:px-2">
                        <BreezeLabel :for="`horarios-${index-1}`" value="Horário" />
                        <BreezeInput :id="`horarios-${index-1}`" type="time" class="block w-full"
                          v-model="form.horarios[index-1]" />
                      </div>
                    </div>
                  </div>
                </template>
              </Fieldset>

              <Fieldset v-if="props.paciente != undefined">
                <template #rotulo>
                  Atendimentos
                </template>
                <template #conteudo>
                  <div class="lg:grid lg:grid-cols-5">
                    <div class="mt-4 lg:pr-2">
                      <BreezeLabel for="qtd_total_atendimentos" value="Total do plano" />
                      <BreezeInput id="qtd_total_atendimentos" type="text" class="mt-1 block w-full" disabled
                        :value="paciente.atendimentos_total" />
                    </div>
                    <div class="mt-4 lg:pl-2">
                      <BreezeLabel for="qtd_atendimentos_agendados" value="Agendados" />
                      <BreezeInput id="qtd_atendimentos_agendados" type="text" class="mt-1 block w-full" disabled
                        :value="paciente.atendimentos_agendados" />
                    </div>
                    <div class="mt-4 lg:pl-2">
                      <BreezeLabel for="qtd_atendimentos_cumpridos" value="Cumpridos" />
                      <BreezeInput id="qtd_atendimentos_cumpridos" type="text" class="mt-1 block w-full" disabled
                        :value="paciente.atendimentos_cumpridos" />
                    </div>
                    <div class="mt-4 lg:pl-2">
                      <BreezeLabel for="qtd_atendimentos_faltados" value="Faltados" />
                      <BreezeInput id="qtd_atendimentos_faltados" type="text" class="mt-1 block w-full" disabled
                        :value="paciente.atendimentos_faltados" />
                    </div>
                    <div class="mt-4 lg:pl-2">
                      <BreezeLabel for="qtd_atendimentos_restantes" value="Restantes" />
                      <BreezeInput id="qtd_atendimentos_restantes" type="text" class="mt-1 block w-full" disabled
                        :value="(paciente.atendimentos - (paciente.atendimentos_agendados+paciente.atendimentos_cumpridos+paciente.atendimentos_faltados) )" />
                    </div>
                  </div>
                </template>
              </Fieldset>

              <div class="flex items-center justify-end mt-4">
                <Link
                  class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:shadow-outline-slate transition ease-in-out duration-150"
                  :href="route('pacientes')">
                Voltar
                </Link>
                <Link v-if="paciente !== undefined"
                  class="inline-flex items-center ml-4 px-4 py-2 bg-rose-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:shadow-outline-rose transition ease-in-out duration-150"
                  :href="route('deletarPaciente', [props.paciente.id])">
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