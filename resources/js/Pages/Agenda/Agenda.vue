<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import AgendaCard from '@/Components/AgendaCard.vue';
import FAB from '@/Components/FloatingActionButton.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';

const props = defineProps({
    agendas: Object,
    status: String
});

</script>

<template>
    <Head title="Agenda" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-sky-800 leading-tight">
                Agenda
            </h2>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <FAB model="Compromisso" rota="adicionarAgenda"></FAB>
                        <AgendaCard v-for="(agenda, index) in agendas" 
                                :key="index" 
                                :date="agenda.date"
                                :time="agenda.time"
                                :id="agenda.id"
                                :paciente="agenda.paciente_id" 
                                :atividade="agenda.atividade_id"
                                :fisio="agenda.user_id"
                                :aparelho="agenda.aparelho_id"
                                :completado=false />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>