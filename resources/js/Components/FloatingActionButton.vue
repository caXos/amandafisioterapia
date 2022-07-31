<script setup>
import { computed } from 'vue';

const props = defineProps(['model', 'acao']);

const titulo = computed(() => 'Adicionar '+props.model);

function addClickEvent() {
    $('#fab').click(props.acao);
}
</script>
<script>

</script>

<template>
    <div id="fab" class="w-10 h-10 fixed bottom-5 right-5 rounded-full bg-sky-400 cursor-pointer hover:scale-125" :title="titulo" type="button" onClick="function () {$('#add-agenda-modal').show();};">
        <span class="material-symbols-outlined text-sky-200 pl-2 pt-2">add</span>
    </div>
</template>
