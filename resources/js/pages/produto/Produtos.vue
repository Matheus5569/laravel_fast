<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import Icon from '@/components/Icon.vue';
import AcoesTabelaProduto from '@/components/produtos/AcoesTabelaProduto.vue';
import ButtonCriarProduto from '@/components/produtos/ButtonCriarProduto.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

type Produto = Record<string, any>;
const page = usePage();
const produtosList = computed<Produto[]>(() => {
    return page.props.produtos ?? [];
});

// Controle da modal
const produtoSelecionado = ref<Produto | null>(null);

</script>

<template>

    <Head title="Produtos" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Heading title="Produtos" description="Lista de produtos cadastrados" />

            <!-- ÁREA DO BOTÃO DE CRIAR -->
            <div class="md:grid-cols 4 grid-cols-1">
                <ButtonCriarProduto/>
                
            </div>

            <!-- Tabela -->
            <div class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4">
                <Table v-if="produtosList.length > 0">
                    <TableHeader>
                        <TableRow>
                            <TableHead>Nome</TableHead>
                            <TableHead>Preço</TableHead>
                            <TableHead>Ações</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow v-for="produto in produtosList" :key="produto.id">
                            <TableCell>{{ produto.nome }}</TableCell>
                            <TableCell>R$ {{ produto.preco }}</TableCell>
                            <TableCell>

                                <div class="flex gap-2">
                                    <!-- Botão Editar -->
                                    <AcoesTabelaProduto :produto="produto"/>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <!-- Mensagem quando não há produtos -->
                <div v-else class="flex flex-col items-center justify-center py-20">
                    <Icon name="package" class="h-16 w-16 text-muted-foreground" />
                    <p class="text-muted-foreground">Nenhum produto encontrado.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>