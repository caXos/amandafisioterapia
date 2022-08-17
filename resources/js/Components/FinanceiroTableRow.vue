<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    lancamento: Object,
});

</script>

<script>
let formatBrazilianReal = Intl.NumberFormat('pt-BR');
function editarLancamento(id) {
    alert('Editar lançamento '+ id);
}
function deletarLancamento(id) {
    // console.log(id);
    // axios.delete(route('deletarFinanceiro', [id])).then(resp => {
    //     console.log(resp);
    //     if (resp.status === 200) alert('Lançamento deletado');
    // }, erro => {
    //     console.log("erro", erro);
    // });
    alert('deletar lançamento ' + id);
}
</script>

<template>
    <tr  :class="{
        'bg-green-100 hover:bg-green-100 hover:text-green-900': lancamento.tipo === 1
        ,'bg-red-100 hover:bg-red-100 hover:text-red-900': lancamento.tipo === 2
        }">
        <td>{{ lancamento.dia }}</td>
        <td>{{ lancamento.hora }}</td>
        <td>{{ lancamento.descricao }}</td>
        <td>{{ lancamento.detalhe }}</td>
        <td v-if="parseInt(lancamento.tipo) === 1">Créd</td>
        <td v-else-if="parseInt(lancamento.tipo) === 2">Déb</td>
        <td class="espaco-entre">
            <span v-if="parseInt(lancamento.tipo) === 1"></span>
            <span v-else-if="parseInt(lancamento.tipo) === 2">-</span>
            <span>R$</span>
            <span>{{ lancamento.valor }}</span>
            </td>
        <td>
            <Link :href="route('editarFinanceiro', [lancamento.id])">
            <span class="material-symbols-outlined text-color-inherit mx-1 cursor-pointer"
                :title="'Editar lançamento'">edit</span>
            </Link>
            <span class="material-symbols-outlined mx-2" style="cursor:pointer;" title="Remover" v-on:click="deletarLancamento(lancamento.id)">delete</span>
        </td>
    </tr>
</template>

<style scoped>
.espaco-entre {
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between !important;
}
</style>