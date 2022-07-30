<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    aparelhos: Object,
});
</script>

<template>
    <Head title="Aparelhos" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Aparelhos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Aparelhos
                        <table class="table-auto border border-sky-700">
                            <thead>
                                <th>Nome</th>
                                <th>Descrição</th>
                            </thead>
                            <tbody v-for="aparelho in aparelhos">
                                <tr class="border border-sky-700 hover:bg-sky-200">
                                    <td class="border border-sky-700">{{aparelho.name}}</td>
                                    <td class="border border-sky-700">{{aparelho.description}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
