<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue','atividades']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
</script>

<template>
    <!-- <select class="border-cyan-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm" @input="$emit('update:modelValue', $event.target.value)" ref="input"> -->
    <select class="border-cyan-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm" @change="$emit('update:modelValue', $event.target.value)" ref="input">
        <option selected disabled>Selecione um(a) tipo</option>
        <option value="1">Entrada/Crédito</option>
        <option value="2">Saída/Débito</option>
    </select>
</template>
