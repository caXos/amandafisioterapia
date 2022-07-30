<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    planos: Object
});
</script>
<script>
let formatBrazilianReal = Intl.NumberFormat('pt-BR');
</script>

<template>
    <Head title="Planos" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Planos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Planos
                        <table class="table-auto border border-sky-700">
                            <thead>
                                <th>Nome</th>
                                <th>Tempo</th>
                                <th>Frequência</th>
                                <th>Valor Toral</th>
                            </thead>
                            <tbody v-for="plano in planos">
                                <tr class="border border-sky-700 hover:bg-sky-200">
                                    <td class="border border-sky-700">{{plano.name}}</td>
                                    <td class="border border-sky-700">{{plano.time}}</td>
                                    <td class="border border-sky-700">{{plano.frequency}}</td>
                                    <td class="border border-sky-700">{{ formatBrazilianReal.format(parseFloat(plano.totalValue)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
