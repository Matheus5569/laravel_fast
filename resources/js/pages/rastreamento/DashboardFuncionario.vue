<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import {
    Package,
    Scissors,
    Paintbrush,
    Flame,
    ClipboardList,
    Truck,
    CheckCircle,
} from 'lucide-vue-next';
const page = usePage();

const totalProducao = page.props.totalProducao;
const totalRecorte = page.props.totalRecorte;
const totalPintura = page.props.totalPintura;
const totalEstufa = page.props.totalEstufa;
const totalSeparacao = page.props.totalSeparacao;
const totalCaminhoneiro = page.props.totalCaminhoneiro;
const totalConcluidos = page.props.totalConcluidos;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];

// Loading
const loading = ref(true);
onMounted(() => {
    setTimeout(() => loading.value = false, 1500); // simula 1.5s de loading
});

// Funcionalidades reais do sistema

</script>
<template>

    <AppLayout>

        <div class="p-4">

            <Heading title="Painel da Produção" description="Controle de fabricação" />

            <div class="grid grid-cols-4 gap-4 mt-6">

                <div class="border rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <Package class="h-5 w-5" />
                        <h3>Fazendo Materiais</h3>
                    </div>

                    <p class="text-3xl font-bold">
                        {{ totalProducao }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <Scissors class="h-5 w-5" />
                        <h3>Recorte</h3>
                    </div>

                    <p class="text-3xl font-bold">
                        {{ totalRecorte }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <Paintbrush class="h-5 w-5" />
                        <h3>Pintura</h3>
                    </div>

                    <p class="text-3xl font-bold">
                        {{ totalPintura }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <Flame class="h-5 w-5" />
                        <h3>Estufa</h3>
                    </div>

                    <p class="text-3xl font-bold">
                        {{ totalEstufa }}
                    </p>
                </div>
                <div class="border rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <ClipboardList class="h-5 w-5" />
                        <h3>Separação</h3>
                    </div>

                    <p class="text-3xl font-bold">
                        {{ totalSeparacao }}
                    </p>
                </div>
                <div class="border rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <Truck class="h-5 w-5" />
                        <h3>Aguardando Caminhoneiro</h3>
                    </div>

                    <p class="text-3xl font-bold">
                        {{ totalCaminhoneiro }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <CheckCircle class="h-5 w-5" />
                        <h3>Concluídos</h3>
                    </div>

                    <p class="text-3xl font-bold">
                        {{ totalConcluidos }}
                    </p>
                </div>

            </div>

        </div>

    </AppLayout>

</template>