<script setup>
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
        'bg-green-100 hover:bg-green-100 hover:text-green-900': lancamento.type === 1
        ,'bg-red-100 hover:bg-red-100 hover:text-red-900': lancamento.type === 2
        }">
        <!-- <td>{{ new Date(date).toLocaleDateString() }}</td> -->
        <td>{{ new Date(lancamento.date).toLocaleDateString() }}</td>
        <!-- <td>{{ date.toLocaleDateString() }}</td> -->
        <td>{{ lancamento.time }}</td>
        <td>{{ lancamento.description }}</td>
        <td>{{ lancamento.detail }}</td>
        <td v-if="parseInt(lancamento.type) === 1">Créd</td>
        <td v-else-if="parseInt(lancamento.type) === 2">Déb</td>
        <td class="espaco-entre">
            <span v-if="parseInt(lancamento.type) === 1"></span>
            <span v-else-if="parseInt(lancamento.type) === 2">-</span>
            <span>R$</span>
            <span>{{ formatBrazilianReal.format(parseFloat(lancamento.value)) }}</span>
            </td>
        <td>
            <span class="material-symbols-outlined mx-2" style="cursor:pointer;" title="Editar" v-on:click="editarLancamento(lancamento.id)">edit</span>
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