<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue','planos']);

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
        <option selected disabled>Selecione um plano</option>
        <option v-for="plano in planos" :value="`${plano.id}`" :tempoPHP="`${plano.tempoPHP}`" >{{plano.nome}}</option>
    </select>
</template>
