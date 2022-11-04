<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue'

const props = defineProps({
    compromisso: Object,
    onEditarCompromissoTodo: Function,
    onNotificarCompromissoTodo: Function,
    onCompletarCompromissoTodo: Function,
    onFaltarCompromissoTodo: Function,
    onDeletarCompromissoTodo: Function,
    onRetornarCompromissoTodo: Function,
    onAbrirModalAtendimento: Function
});

defineEmits(['editarCompromissoTodo, notificarCompromissoTodo, completarCompromissoTodo, faltarCompromissoTodo, deletarCompromissoTodo, retornarCompromissoTodo', 'abrirModalAtendimento']);
const qtdLinhas = ref(1)
const qtdColunas = ref(1)

onMounted (function () {
    if (props.compromisso.atendimentos.length <= 3) {
        qtdLinhas.value = 1
        qtdColunas.value = props.compromisso.atendimentos.length
    } else {
        props.compromisso.atendimentos.length%3 === 0 ? qtdLinhas.value = props.compromisso.atendimentos.length/3 : qtdLinhas.value = (props.compromisso.atendimentos.length / 3) + 1
        qtdColunas.value = 3
    }
    
})
</script>

<script>
</script>

<template>
    <div
        class="container shadow-lg shadow-sky-200 text-center text-sky-600 hover:bg-sky-100 transition-all border-2 rounded border-sky-600 mb-5 flex-wrap">
        <div class="bg-sky-100 py-2 border-b-2 border-sky-600 font-bold">
            <span>{{ new Date( new Date(compromisso.dia).setDate( new Date(compromisso.dia).getDate()+ 1 ) ).toLocaleDateString() }}, {{ new Date( new Date(compromisso.dia).setDate( new Date(compromisso.dia).getDate()+ 1 ) ).toLocaleDateString('pt-BR', {weekday: 'long'}) }} - {{ compromisso.hora.substring(0, 5) }}</span>
            <span> - Vagas: {{ compromisso.vagas}}</span>
        </div>
        <div v-if="compromisso.atendimentos.length === 0">
            <Link :href="route('editarCompromisso', [props.compromisso.id])">
                <span class="material-symbols-outlined text-color-inherit m-1 cursor-pointer"
                    :title="'Adicionar compromisso e atendimentos'">add</span>
            </Link>
        </div>
        <div v-else :class="`flex-1 grid grid-cols-${qtdColunas} grid-rows-${qtdLinhas}`">
            <div class="grid grid-rows-4 border border-0 rounded-2xl cursor-pointer hover:bg-sky-200 max-w-fit py-1 px-2 mx-auto"
                v-for="(atendimento, index) in compromisso.atendimentos" :key="index"
                @click="$emit('abrirModalAtendimento', [atendimento, compromisso.dia, compromisso.hora])"
                >
                <div>{{ atendimento.paciente_nome }}</div>
                <div>{{ atendimento.atividade_nome }}</div>
                <div>{{ atendimento.aparelho_nome }}</div>
                <div>{{ atendimento.fisio_nome.split(" ")[0] }}</div>
            </div>
        </div>
        <div v-if="compromisso.atendimentos.length > 0">
            <div class="mt-2 mb-1">
                <Link :href="route('editarCompromisso', [compromisso.id])">
                    <span class="mx-3 my-1 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                        :title="`Editar compromisso todo`" >edit</span>
                </Link>
                <span class="mx-3 my-1 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="`Notificar todos`" @click="$emit('notificarCompromissoTodo', compromisso)">notifications</span>
                <span class="mx-3 my-1 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    title="Marcar todos como completado" @click="$emit('completarCompromissoTodo',compromisso)">done_all</span>
                <span class="mx-3 my-1 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Marcar todos com falta'"
                    @click="$emit('faltarCompromissoTodo', compromisso)">event_busy</span>
                <span class="mx-3 my-1 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Deletar todos sem completar'" @click="$emit('deletarCompromissoTodo', compromisso)">delete</span>
                <span class="mx-3 my-1 material-symbols-outlined text-color-inherit cursor-pointer rounded-full ring-offset-2 hover:ring-2"
                    :title="'Agendar retorno para todos'" @click="$emit('retornarCompromissoTodo', compromisso)">forward</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>