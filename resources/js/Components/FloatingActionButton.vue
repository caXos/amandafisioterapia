<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';

const props = defineProps(['model', 'rota']);

const titulo = computed(() => 'Adicionar '+props.model);

</script>
<script>

</script>

<template>
    <div id="fab" class="w-10 h-10 fixed bottom-5 right-5 rounded-full bg-sky-400 cursor-pointer hover:scale-125" :title="titulo" type="button" >
        <Link :href="route(rota)"><span class="material-symbols-outlined text-sky-200 pl-2 pt-2">add</span></Link>
    </div>
</template>
