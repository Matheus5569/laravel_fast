<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ShoppingCart, Package, CreditCard, CheckCircle, ArrowRight } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard Vendedor', href: '/vendedor-dashboard' },
];

// Loading
const loading = ref(true);
onMounted(() => {
  setTimeout(() => loading.value = false, 1000); // loading rápido
});

// Passos do sistema de vendas
const passosVenda = [
  {
    numero: 1,
    titulo: 'Selecione os Produtos',
    descricao: 'Escolha os produtos que o cliente deseja comprar e adicione ao carrinho.',
    icone: Package,
    cor: 'bg-blue-500'
  },
  {
    numero: 2,
    titulo: 'Finalize a Venda',
    descricao: 'Revise os itens, aplique descontos se necessário e finalize a venda.',
    icone: ShoppingCart,
    cor: 'bg-green-500'
  },
  {
    numero: 3,
    titulo: 'Escolha a Forma de Pagamento',
    descricao: 'Selecione a forma de pagamento: dinheiro, cartão, pix ou fiado.',
    icone: CreditCard,
    cor: 'bg-purple-500'
  },
  {
    numero: 4,
    titulo: 'Confirme e Conclua',
    descricao: 'Confirme os dados e conclua a venda. O comprovante será gerado automaticamente.',
    icone: CheckCircle,
    cor: 'bg-orange-500'
  }
];

// Funcionalidades principais
const funcionalidades = [
  {
    titulo: 'Nova Venda',
    descricao: 'Clique aqui para iniciar uma nova venda',
    rota: '/vendas/nova',
    icone: ShoppingCart,
    cor: 'bg-gradient-to-r from-green-500 to-green-600'
  },
  {
    titulo: 'Consultar Produtos',
    descricao: 'Veja a lista de produtos disponíveis para venda',
    rota: '/produtos',
    icone: Package,
    cor: 'bg-gradient-to-r from-blue-500 to-blue-600'
  }
];
</script>

<template>
  <Head title="Dashboard Vendedor" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <!-- LOADING -->
    <div v-if="loading" class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center">
          <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-current border-r-transparent text-sidebar-foreground/50" role="status"></div>
          <p class="mt-4 text-sm text-sidebar-foreground/70">Carregando...</p>
        </div>
      </div>
    </div>

    <!-- CONTEÚDO DO VENDEDOR -->
    <div v-else class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6 max-w-5xl mx-auto w-full">
      
      <!-- Cabeçalho simples -->
      <div class="text-center mb-4">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-200">
          Sistema de Vendas
        </h1>
        <p class="text-muted-foreground mt-2">
          Bem-vindo ao módulo de vendas. Aqui você pode realizar vendas de forma rápida e simples.
        </p>
      </div>

      <!-- Botões principais em destaque -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div v-for="func in funcionalidades" :key="func.titulo"
             @click="router.get(func.rota)"
             class="group cursor-pointer rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1"
             :class="func.cor">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <component :is="func.icone" class="w-10 h-10" />
              <div>
                <h2 class="text-xl font-bold">{{ func.titulo }}</h2>
                <p class="text-white/80 text-sm mt-1">{{ func.descricao }}</p>
              </div>
            </div>
            <ArrowRight class="w-6 h-6 group-hover:translate-x-1 transition-transform" />
          </div>
        </div>
      </div>

      <!-- Explicação do sistema de vendas -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border p-6">
        <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
          <ShoppingCart class="w-6 h-6 text-green-500" />
          Como funciona o sistema de vendas?
        </h2>

        <!-- Passos da venda -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div v-for="passo in passosVenda" :key="passo.numero"
               class="relative p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/50">
            
            <!-- Número do passo em círculo -->
            <div class="absolute -top-3 -left-3 w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm"
                 :class="passo.cor">
              {{ passo.numero }}
            </div>
            
            <div class="mt-2">
              <div class="flex justify-center mb-3">
                <div class="p-2 rounded-full" :class="`${passo.cor} bg-opacity-20`">
                  <component :is="passo.icone" class="w-6 h-6" :class="passo.cor.replace('bg-', 'text-')" />
                </div>
              </div>
              <h3 class="font-semibold text-center mb-2">{{ passo.titulo }}</h3>
              <p class="text-xs text-center text-muted-foreground">
                {{ passo.descricao }}
              </p>
            </div>
          </div>
        </div>

        <!-- Informações adicionais -->
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
          <h3 class="font-medium text-blue-800 dark:text-blue-300 mb-2">📋 Informações importantes:</h3>
          <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-400">
            <li class="flex items-start gap-2">
              <span class="text-blue-500">•</span>
              <span>Você pode adicionar múltiplos produtos ao carrinho antes de finalizar a venda</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="text-blue-500">•</span>
              <span>O sistema calcula automaticamente o total da compra</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="text-blue-500">•</span>
              <span>Após finalizada, a venda não pode ser editada, apenas cancelada</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="text-blue-500">•</span>
              <span>Todas as vendas ficam registradas no histórico para consulta posterior</span>
            </li>
          </ul>
        </div>

        <!-- Dica rápida -->
        <div class="mt-4 text-center text-sm text-muted-foreground">
          💡 Para iniciar uma venda, clique no botão <span class="font-semibold text-green-600 dark:text-green-400">"Nova Venda"</span> acima
        </div>
      </div>

      <!-- Rodapé simples -->
      <div class="text-center text-xs text-muted-foreground mt-4">
        Versão 1.0 - Sistema de Vendas
      </div>
    </div>
  </AppLayout>
</template>