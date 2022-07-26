<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import CompromissoCard from '@/Components/CompromissoCard.vue';
import FAB from '@/Components/FloatingActionButton.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted, computed, ref, shallowReadonly } from 'vue';
import CompromissoModal from '@/Components/CompromissoModal.vue';
import AtendimentoModal from '@/Components/AtendimentoModal.vue';

const props = defineProps({
    compromissos: Object,
    status: String,
});

const localStatus = ref(props.status)

const modal = ref(false);
const modalAtendimento = ref(false);

const modalConteudo = ref({
    'titulo': null,
    'primeiraLinha': null,
    'segundaLinha': null,
    'compromisso': null,
    'rota': null
});

const modalAtendimentoConteudo = ref({
    'atendimento': null,
    'dia': null,
    'hora': null
});

const form = useForm({});

function abrirModalNotificarCompromissoTodo(compromisso) {
    console.log(compromisso)
    if (compromisso.atendimentos.length === 1) {
        modalConteudo.value.titulo = 'Notificar paciente'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja notificar o(a) paciente do seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Um paciente será notificado.'
    } else {
        modalConteudo.value.titulo = 'Notificar pacientes'
        modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja notificar os(as) pacientes do seguinte compromisso?'
        modalConteudo.value.segundaLinha = 'Serão notificados(as) ' + compromisso.atendimentos.length + ' pacientes.'
    }
    modalConteudo.value.compromisso = compromisso
    modalConteudo.value.rota = 'notificarCompromisso/'+compromisso.id
    modal.value = true
}

function abrirModalCompletarCompromissoTodo(compromisso) {
    let titulo = null
    let primeiraLinha = null
    let segundaLinha = null
    let compromissoString = new Date(compromisso.dia).toLocaleDateString() +', '+ new Date(compromisso.dia).toLocaleString('pt-BR', {weekday: 'long'}) +', às '+ compromisso.hora.substring(0, 5)
    if (compromisso.atendimentos.length === 1) {
        titulo= 'Registrar atendimento completado'
        primeiraLinha = 'Tem certeza de que deseja registrar o seguinte compromisso como completado?'
        segundaLinha = 'Um atendimento será marcado como completado.'
    } else {
        titulo = 'Registrar atendimentos completados'
        primeiraLinha = 'Tem certeza de que deseja registrar o seguinte compromisso como completado?'
        segundaLinha = 'Serão registrados como completado ' + compromisso.atendimentos.length + ' atendimentos.'
    }
    Swal.fire({
        title: titulo,
        html: `<span>${primeiraLinha}</span><br/><br/>${compromissoString}<br/><br/><span>${segundaLinha}</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#14532d',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, completar',
        cancelButtonText: 'Não, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('completarCompromisso',[compromisso.id]), {
                onSuccess: () => {
                    Swal.fire ({
                        title: 'Sucesso',
                        icon: 'success',
                        text: 'Compromisso completado!'
                    })
                },
                onError: () => {
                    Swal.fire ({
                        title: 'Erro',
                        icon: 'error',
                        text: 'Erro ao completar compromisso'
                    })
                },
                onFinish: () => {
                    form.reset()
                }
            })
        }
    })
}

function abrirModalFaltarCompromissoTodo(compromisso) {
    let titulo = null
    let primeiraLinha = null
    let segundaLinha = null
    let compromissoString = new Date(compromisso.dia).toLocaleDateString() +', '+ new Date(compromisso.dia).toLocaleString('pt-BR', {weekday: 'long'}) +', às '+ compromisso.hora.substring(0, 5)
    if (compromisso.atendimentos.length === 1) {
        titulo= 'Registrar falta'
        primeiraLinha = 'Tem certeza de que deseja registrar falta para o seguinte compromisso?'
        segundaLinha = 'Um atendimento será registrado como falta.'
    } else {
        titulo = 'Registrar faltas'
        primeiraLinha = 'Tem certeza de que deseja registrar faltas para o seguinte compromisso?'
        segundaLinha = 'Serão registrados como falta ' + compromisso.atendimentos.length + ' atendimentos.'
    }
    Swal.fire({
        title: titulo,
        html: `<span>${primeiraLinha}</span><br/><br/>${compromissoString}<br/><br/><span>${segundaLinha}</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, registrar falta',
        cancelButtonText: 'Não, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.post(route('faltarCompromisso',[compromisso.id]), {
                onSuccess: () => {
                    Swal.fire ({
                        title: 'Sucesso',
                        icon: 'success',
                        text: 'Compromisso registrado como falta!'
                    })
                },
                onError: () => {
                    Swal.fire ({
                        title: 'Erro',
                        icon: 'error',
                        text: 'Erro ao registrar compromisso como falta'
                    })
                },
                onFinish: () => {
                    form.reset()
                }
            })
        }
    })
}

function abrirModalDeletarCompromissoTodo(compromisso) {
    let titulo = null
    let primeiraLinha = null
    let segundaLinha = null
    let compromissoString = new Date(compromisso.dia).toLocaleDateString() +', '+ new Date(compromisso.dia).toLocaleString('pt-BR', {weekday: 'long'}) +', às '+ compromisso.hora.substring(0, 5)
    if (compromisso.atendimentos.length === 1) {
        titulo= 'Desmarcar atendimento'
        primeiraLinha = 'Tem certeza de que deseja desmarcar o seguinte compromisso?'
        segundaLinha = 'Um atendimento será desmarcado.'
    } else {
        titulo = 'Desmarcar atendimentos'
        primeiraLinha = 'Tem certeza de que deseja desmarcar o seguinte compromisso?'
        segundaLinha = 'Serão desmarcados ' + compromisso.atendimentos.length + ' atendimentos.'
    }
    Swal.fire({
        title: titulo,
        html: `<span>${primeiraLinha}</span><br/><br/>${compromissoString}<br/><br/><span>${segundaLinha}</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, deletar',
        cancelButtonText: 'Não, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('deletarCompromisso',[compromisso.id]), {
                onSuccess: () => {
                    Swal.fire ({
                        title: 'Sucesso',
                        icon: 'success',
                        text: 'Compromisso removido!'
                    })
                },
                onError: () => {
                    Swal.fire ({
                        title: 'Erro',
                        icon: 'error',
                        text: 'Erro ao remover compromisso'
                    })
                },
                onFinish: () => {
                    form.reset()
                }
            })
        }
    })
}

function abrirModalRetornarCompromissoTodo(compromisso) {
    let compromissosString = '{'
    for (let i=0; i<props.compromissos.length; i++) {
        if (props.compromissos[i].id !== compromisso.id) {
            compromissosString += `"${props.compromissos[i].id}":"${props.compromissos[i].dia} - ${props.compromissos[i].hora.substring(0,5)} - ${props.compromissos[i].vagas}/${props.compromissos[i].vagas_preenchidas}"`
            if (i < (props.compromissos.length-1)) compromissosString += ','
        }
    }
    compromissosString += '}'
    if (compromissosString === '{}') compromissosString = `{"0":"Não há outros compromissos!"}`
    let compromissosJson = JSON.parse(compromissosString)

    let titulo = null
    let primeiraLinha = null
    let segundaLinha = null
    let compromissoString = new Date(compromisso.dia).toLocaleDateString() +', '+ new Date(compromisso.dia).toLocaleString('pt-BR', {weekday: 'long'}) +', às '+ compromisso.hora.substring(0, 5)
    if (compromisso.atendimentos.length === 1) {
        titulo= 'Agendar retorno'
        primeiraLinha = 'Tem certeza de que deseja agendar retorno para do seguinte compromisso?'
        segundaLinha = 'Escolha abaixo se quer registrar falta ou presença para o atendimento deste compromisso:'
    } else {
        titulo = 'Agendar retornos'
        primeiraLinha = 'Tem certeza de que deseja agendar retorno do seguinte compromisso?'
        segundaLinha = 'Escolha abaixo se quer registrar falta ou presença para os ' + compromisso.atendimentos.length + ' atendimentos.'
    }
    Swal.fire({
        title: titulo,
        html: `<span>${primeiraLinha}</span><br/><br/>${compromissoString}<br/><br/><span>${segundaLinha}</span>`,
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        denyButtonColor: '#d33',
        confirmButtonText: 'Registrar presenca e retorno',
        denyButtonText: 'Registrar falta e retorno',
        cancelButtonText: 'Cancelar',
        input: 'select',
        inputOptions: compromissosJson
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Confirmado', result)
            //Verificar se value === 0 , ou seja, não há outros atendimentos. Caso true, tem que informar que nada foi feito. Ou nem mostrar a opção, na verdade... mostrar um outro swal no lugar... um para criar atendimento
            form.delete(route('completarCompromissoComRetorno', [compromisso.id, parseInt(result.value)]), {
                onSucess: () => {
                    console.log('sucesso no formulário')
                },
                onFinish: () => {
                    console.log('formulário finalizado')
                }
            })
            /**
             * Object {
             *      isConfirmed: true,
             *      isDenied: false,
             *      isDismissed: false,
             *      value: "1"
             *  }
             */
            // form.delete(route('deletarCompromisso',[compromisso.id]), {
            //     onSuccess: () => {
            //         Swal.fire ({
            //             title: 'Sucesso',
            //             icon: 'success',
            //             text: 'Compromisso removido!'
            //         })
            //     },
            //     onError: () => {
            //         Swal.fire ({
            //             title: 'Erro',
            //             icon: 'error',
            //             text: 'Erro ao remover compromisso'
            //         })
            //     },
            //     onFinish: () => {
            //         form.reset()
            //     }
            // })
        }
        else {
            if (result.isDenied) {
                console.log('Denied', result)
                /**
                 * Object {
                 *      isConfirmed: true,
                 *      isDenied: false,
                 *      isDismissed: false,
                 *      value: "1"
                 *  }
                 */
                // form.delete(route('deletarCompromisso',[compromisso.id]), {
                //     onSuccess: () => {
                //         Swal.fire ({
                //             title: 'Sucesso',
                //             icon: 'success',
                //             text: 'Compromisso removido!'
                //         })
                //     },
                //     onError: () => {
                //         Swal.fire ({
                //             title: 'Erro',
                //             icon: 'error',
                //             text: 'Erro ao remover compromisso'
                //         })
                //     },
                //     onFinish: () => {
                //         form.reset()
                //     }
                // })
            }
        }
    })



    // if (compromisso.atendimentos.length === 1) {
    //     modalConteudo.value.titulo = 'Agendar retorno'
    //     modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja agendar retorno do seguinte compromisso?'
    //     modalConteudo.value.segundaLinha = 'Será agendado retorno para um atendimento.'
    // } else {
    //     modalConteudo.value.titulo = 'Agendar retornos'
    //     modalConteudo.value.primeiraLinha = 'Tem certeza de que deseja agendar retorno do seguinte compromisso?'
    //     modalConteudo.value.segundaLinha = 'Serão agendados retornos para ' + compromisso.atendimentos.length + ' atendimentos.'
    // }
    // modalConteudo.value.compromisso = compromisso
    // modalConteudo.value.rota = 'notificarCompromisso/'+compromisso.id //alterar para faltar compromisso
    // modal.value = true
}

function abrirModalAtendimento(atendimento) {
    modalAtendimentoConteudo.value.atendimento = atendimento[0]
    modalAtendimentoConteudo.value.dia = atendimento[1]
    modalAtendimentoConteudo.value.hora = atendimento[2]
    modalAtendimento.value = true;
}

function fecharModal() {
    modal.value = false
}

function fecharModalAtendimento() {
    modalAtendimento.value = false
}

</script>

<script>

</script>

<template>

    <Head title="Agenda" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <p class="font-semibold text-sky-800 leading-tight">
                Agenda <span v-if="teste != undefined">teste</span>
            </p>
            <div v-if="localStatus != undefined" class="my-2 font-medium text-sm text-green-600 text-center">
                {{ localStatus }}
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <FAB model="Compromisso" rota="adicionarCompromisso"></FAB>
                        <p v-if="compromissos.length == 0" class="text-sky-800 text-center">Não há compromissos. Use o botão
                            abaixo para incluir compromissos na agenda.</p>
                        <CompromissoCard v-else v-for="(compromisso, index) in compromissos" :key="index" :compromisso="compromisso"
                            @notificarCompromissoTodo="abrirModalNotificarCompromissoTodo"
                            @completarCompromissoTodo="abrirModalCompletarCompromissoTodo"
                            @faltarCompromissoTodo="abrirModalFaltarCompromissoTodo"
                            @deletarCompromissoTodo="abrirModalDeletarCompromissoTodo"
                            @retornarCompromissoTodo="abrirModalRetornarCompromissoTodo"

                            @abrirModalAtendimento="abrirModalAtendimento"
                            />
                        <CompromissoModal v-if="modal == true" v-on:fecharModal="fecharModal" 
                            :titulo="modalConteudo.titulo"
                            :compromisso="modalConteudo.compromisso"
                            :primeiraLinha="modalConteudo.primeiraLinha"
                            :segundaLinha="modalConteudo.segundaLinha"
                            :rota="modalConteudo.rota"
                            />
                        <AtendimentoModal v-if="modalAtendimento == true"
                            :atendimento="modalAtendimentoConteudo.atendimento"
                            :dia="modalAtendimentoConteudo.dia"
                            :hora="modalAtendimentoConteudo.hora"
                            @fecharModalAtendimento="fecharModalAtendimento"
                            />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>