<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';
import { ref } from 'vue'

const props = defineProps({
    date: String,
    time: String,
    id: Number,
    paciente_id: String,
    atividade_id: String,
});
</script>

<script>

</script>

<template>
    <div class="container shadow-lg shadow-sky-200 text-center text-sky-600 scale-95 hover:bg-sky-200 hover:scale-100 transition-all">
        <div class="grid grid-cols-4 mb-5 border-2 rounded border-sky-600 divide-x-2 divide-inherit items-center">
            <div class="grid grid-rows-2">
                <!-- <div>Nome Paciente {{ id }}</div> -->
                <div>{{paciente_id}}</div>
                <div>{{date}} {{time}}</div>
            </div>
            <div class="flex shrink justify-center">
                <img src="../../../public/img/abstract-user-flat-4.svg" style="height:50px;width:50px;" :title="`${paciente_id}`">
            </div>
            <div>
                {{atividade_id}}
            </div>
            <div>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer" :title="`Notificar ${paciente_id}`">notifications</span>
                <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer" title="Marcar como completada">done</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>