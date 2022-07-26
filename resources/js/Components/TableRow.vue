<script setup>
const props = defineProps({
    date: String,
    time: String,
    description: String,
    detail: String,
    type: Number,
    value: String,
    loggedUser: Number
});
</script>

<script>
let formatBrazilianReal = Intl.NumberFormat('pt-BR');
</script>

<template>
    <tr  :class="{
        'bg-green-100 hover:bg-green-100 hover:text-green-900': type === 1
        ,'bg-red-100 hover:bg-red-100 hover:text-red-900': type === 2
        }">
        <td>{{ new Date(date).toLocaleDateString() }}</td>
        <!-- <td>{{ date.toLocaleDateString() }}</td> -->
        <td>{{ time }}</td>
        <td>{{ description }}</td>
        <td>{{ detail }}</td>
        <td v-if="parseInt(type) === 1">Créd</td>
        <td v-else-if="parseInt(type) === 2">Déb</td>
        <td class="espaco-entre">
            <span v-if="parseInt(type) === 1"></span>
            <span v-else-if="parseInt(type) === 2">-</span>
            <span>R$</span>
            <span>{{ formatBrazilianReal.format(parseFloat(value)) }}</span>
            </td>
        <td>
            <span class="material-symbols-outlined mx-2" style="cursor:pointer;" title="Editar">edit</span>
            <span class="material-symbols-outlined mx-2" style="cursor:pointer;" title="Remover">delete</span>
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