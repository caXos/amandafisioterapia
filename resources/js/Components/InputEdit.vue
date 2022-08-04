<script setup>
import { onMounted, ref } from 'vue';

// defineProps(['modelValue']);
const props = defineProps(['modelValue', 'valorParaEditar','container']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    $('#'+props.container).val(props.valorParaEditar);
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
</script>

<template>
    <input class="border-cyan-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>
