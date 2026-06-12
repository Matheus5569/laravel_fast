<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3'; // ✅ Correto: importar do Inertia
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { User, Users, Gift, ShoppingCart, Truck } from 'lucide-vue-next'; 
import Icon from '@/components/Icon.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
];

// Loading
const loading = ref(true);
onMounted(() => {
    setTimeout(() => loading.value = false, 1500); // simula 1.5s de loading
});

// Funcionalidades reais do sistema
const funcionalidades = [
    {
        titulo: 'Gerentes',
        descricao: 'Cadastre, edite ou exclua gerentes. Gerencie os dados de cada gerente do sistema.',
        icone: User,
    },
    {
        titulo: 'Caminhoneiros',
        descricao: 'Cadastre, edite ou exclua caminhoneiros. Gerencie os dados de cada caminhoneiro do sistema.',
        icone: User,
    },
    {
        titulo: 'Clientes',
        descricao: 'Cadastre novos clientes e edite informações.',
        icone: Users,
    },
    {
        titulo: 'Funcionários',
        descricao: 'Cadastre, edite ou exclua funcionários. Gerencie os dados de cada funcionário do sistema.',
        icone: Users,
    },
    {
        titulo: 'Baias',
        descricao: 'Cadastre, edite ou exclua baias. Gerencie os dados de cada baia do sistema.',
        icone: Users,
    },
    {
        titulo: 'Produtos',
        descricao: 'Cadastre produtos, defina valores e organize informações para venda.',
        icone: Gift,
    },
    {
        titulo: 'Vendas',
        descricao: 'Realize vendas selecionando produtos, gerencie itens do carrinho e finalize transações.',
        icone: ShoppingCart,
    },
    {
        titulo: 'Rastreamentos',
        descricao: 'Veja os rastreamentos em tempo real.',
        icone: Truck,
    },
];
</script>
<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <!-- LOADING -->
        <div v-if="loading" class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex items-center justify-center min-h-[60vh]">
                <div class="text-center">
                    <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] text-sidebar-foreground/50"
                        role="status"></div>
                    <p class="mt-4 text-sm text-sidebar-foreground/70">Carregando dashboard...</p>
                </div>
            </div>
        </div>

        <!-- CONTEÚDO -->
        <div v-else class="flex h-full flex-1 flex-col gap-8 overflow-x-auto rounded-xl p-4">

            <!-- Título -->
            <div class="text-center">
                <h1 class="text-3xl font-bold">Bem-vindo ao Sistema de Vendas</h1>
                <p class="text-muted-foreground mt-2">
                    Aqui você pode gerenciar vendedores, clientes, produtos e realizar vendas de forma simples.
                </p>
            </div>

            <!-- Funcionalidades em cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="func in funcionalidades" :key="func.titulo"
                    class="border rounded-xl p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-200 bg-white dark:bg-gray-800">
                    <div class="text-5xl text-yellow-400 mb-4">
                         <component :is="func.icone" />
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ func.titulo }}</h3>
                    <p class="text-muted-foreground text-sm">{{ func.descricao }}</p>
                </div>
            </div>

            <!-- Chamada final -->
            <div class="text-center mt-8">
                <p class="text-lg font-medium">
                    Comece agora mesmo a gerenciar seu negócio de forma prática e eficiente!
                </p>
            </div>

        </div>
    </AppLayout>
</template>

<style scoped>
@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}

.animate-shimmer {
    transform: translateX(-100%);
    animation: shimmer 2s infinite;
}
</style>