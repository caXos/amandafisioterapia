<script setup>
import { onMounted, ref } from 'vue';

// defineProps(['modelValue','pacientes', 'selectedIndex']);
// const props = defineProps(['modelValue','pacientes', 'selectedIndex']);
defineProps(['modelValue','pacientes']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    // $('#paciente').val(props.selectedIndex);
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

</script>

<template>
    <!-- <select class="border-cyan-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm" @input="$emit('update:modelValue', $event.target.value)" ref="input"> -->
    <select id="paciente-select" class="border-cyan-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm" @change="$emit('update:modelValue', $event.target.value)" ref="input">
        <option value="0" selected disabled>Selecione um(a) paciente</option>
        <option v-for="paciente in pacientes" :value="`${paciente.id}`">{{paciente.nome}}</option>
    </select>
</template>
