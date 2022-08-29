<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue'

const props = defineProps({
    // date: String,
    // time: String,
    // id: Number,
    // paciente: String,
    // atividade: String,
    // aparelho: String,
    // fisio: String,
    // completado: Boolean,
    agenda: Object
});

// const localCompletado = ref(props.completado);

</script>

<script>
function completarCompromisso(id) {
    // axios.post(route('completarCompromisso', [id])).then(resp => {
    //     // console.log(resp.status);
    //     if (resp.status === 200) this.localCompletado = true;
    // }, erro => {
    //     console.log("erro", erro);
    // });
    // this.localCompletado = true;
}
function deletarCompromisso(id) {
    // axios.post(route('like',[id]))
    alert('deletar compromisso ' + id);
}

function agendarRetorno(paciente) {
    // axios.post(route('like',[id]))
    alert('agendar retorno para o paciente ' + paciente);
}

function notificarPaciente(time) {
    // axios.post(route('like',[id]))
    alert('Notificar paciente sobre compromisso às ' + time);
}

</script>

<template>
    <div
        class="container shadow-lg shadow-sky-200 text-center text-sky-600 hover:bg-sky-100 transition-all border-2 rounded border-sky-600 mb-5">
        <div class="bg-sky-100">{{ new Date(agenda.dia).toLocaleDateString() }} - {{ agenda.hora.substring(0, 5) }}</div>
        <div v-if="agenda.atendimentos.length === 0">
            <span class="material-symbols-outlined text-color-inherit m-1 cursor-pointer"
            :title="'Adicionar compromisso e atendimentos'">add</span>
        </div>
        <div v-else :class="{
            'flex-1 grid grid-cols-1': agenda.atendimentos.length === 1
            , 'flex-1 grid grid-cols-2': agenda.atendimentos.length === 2
            , 'flex-1 grid grid-cols-3': agenda.atendimentos.length === 3
        }">
            <div class="grid grid-rows-4 border border-0 rounded-full cursor-pointer hover:bg-sky-200"
                v-for="(atendimento, index) in agenda.atendimentos" :key="index">
                <div>{{ atendimento.paciente_nome }}</div>
                <div>{{ atendimento.atividade_nome }}</div>
                <div>{{ atendimento.aparelho_nome }}</div>
                <div>{{ atendimento.fisio_nome.split(" ")[0] }}</div>
            </div>
        </div>
        <div v-if="agenda.atendimentos.length > 0">
            <!-- <Link :href="route('editarAgenda', [id])">
            <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                :title="'Editar compromisso'">edit</span>
            </Link>
            <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                :title="`Notificar ${paciente}`" v-on:click="notificarPaciente(this.time)">notifications</span>
            <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                title="Marcar como completado" v-on:click="completarCompromisso(this.id)">done</span>
            <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                :title="'Deletar sem completar'" v-on:click="deletarCompromisso(this.id)">delete</span>
            <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer" :title="'Agendar Retorno'"
                v-on:click="agendarRetorno(this.paciente)">forward</span> -->
            <div>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    :title="`Editar compromisso`">edit</span>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    :title="`Notificar todos`">notifications</span>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    title="Marcar como completado">done</span>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    :title="'Deletar sem completar'">delete</span>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    :title="'Agendar Retorno'">forward</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>