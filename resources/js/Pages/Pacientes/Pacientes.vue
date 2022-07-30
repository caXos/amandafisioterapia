<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    pacientes: Object,
});
</script>

<template>
    <Head title="Pacientes" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pacientes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Pacientes
                        <table class="table-auto border border-sky-700">
                            <thead>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Fisio</th>
                            </thead>
                            <tbody v-for="paciente in pacientes">
                                <tr class="border border-sky-700 hover:bg-sky-200">
                                    <td class="border border-sky-700">{{paciente.name}}</td>
                                    <td class="border border-sky-700">{{paciente.email}}</td>
                                    <td class="border border-sky-700">{{paciente.user_id}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
