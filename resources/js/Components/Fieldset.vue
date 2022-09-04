<script setup>
defineProps(['value']);
</script>

<template>
    <div class="mt-4 border border-cyan-300 p-2 rounded-md shadow-sm placeholder:text-black relative">
        <div class="relative -top-5 bg-white w-fit text-sky-700"><slot name="rotulo"></slot></div>
        <slot name="conteudo"></slot>
    </div>
</template>
