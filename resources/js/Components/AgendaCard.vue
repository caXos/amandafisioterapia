<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue'

const props = defineProps({
    date: String,
    time: String,
    id: Number,
    paciente: String,
    atividade: String,
    aparelho: String,
    fisio: String,
    completado: Boolean,
});

const localCompletado = ref(props.completado);

</script>

<script>
function completarCompromisso(id) {
    axios.post(route('completarCompromisso', [id])).then(resp => {
        // console.log(resp.status);
        if (resp.status === 200) this.localCompletado = true;
    }, erro => {
        console.log("erro", erro);
    });
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
    <div v-if="localCompletado == false"
        class="container shadow-lg shadow-sky-200 text-center text-sky-600 hover:bg-sky-200 transition-all">
        <div class="grid grid-cols-5 mb-5 border-2 rounded border-sky-600 divide-x-2 divide-inherit items-center content-center">
            <div>{{ date }} {{ time }}</div>
            <div class="grid grid-rows-2 items-center">
                <div class="flex shrink justify-center">
                    <img src="../../../public/img/abstract-user-flat-4.svg" style="height:50px;width:50px;"
                        :title="`${paciente}`">
                </div>
                <div>{{ paciente }}</div>
            </div>
            <div class="grid grid-rows-2 items-center">
                <div>{{ atividade }}</div>
                <div v-if="aparelho !== null">{{ aparelho }}</div>
            </div>
            <div>{{ fisio }}</div>
            <div>
                <Link :href="route('editarAgenda',[id])">
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    :title="'Editar compromisso'">edit</span>
                </Link>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    :title="`Notificar ${paciente}`" v-on:click="notificarPaciente(this.time)">notifications</span>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    title="Marcar como completado" v-on:click="completarCompromisso(this.id)">done</span>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    :title="'Deletar sem completar'" v-on:click="deletarCompromisso(this.id)">delete</span>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                    :title="'Agendar Retorno'" v-on:click="agendarRetorno(this.paciente)">forward</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>