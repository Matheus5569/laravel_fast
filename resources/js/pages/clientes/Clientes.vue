<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import Icon from '@/components/Icon.vue';
import AcoesTabelaCliente from '@/components/produtos/AcoesTabelaCliente.vue';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import clientes from '@/routes/clientes';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

type Cliente = Record<string, any>;
const page = usePage();
const clientesList = computed<Cliente[]>(() => {
    return page.props.clientes ?? [];
});
console.log(clientesList);
</script>

<template>

    <Head title="Clientes"/>

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Heading title="Clientes" description="Lista de clientes" /> 

            <div class="md:grid-cols 4 grid-cols-1">
                <Link :href="clientes.persistir()">
                    <Button class="bg-yellow-400 text-black hover:bg-yellow-500">
                        <Icon name="plus" />
                        Criar Novo Cliente
                    </Button>
                </Link> 
            </div>

            <div
                class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">

                <Table v-if="clientesList.length > 0">
                    <TableHeader>
                        <TableRow>
                            <TableHead>Nome</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead></TableHead> 
                            <TableHead>Ações</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow v-for="cliente in clientesList" :key="cliente.id">
                            <TableCell>{{ cliente.nome }}</TableCell>
                            <TableCell>{{ cliente.email }}</TableCell>
                            <TableCell></TableCell> 

                            <TableCell>
                                <div class="flex gap-4">
                                    <div class="flex gap-4">
                                        <AcoesTabelaCliente :cliente="cliente" />
                                    </div>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <div v-else class="flex h-full w-full flex-col items-center justify-center gap-4 py-20">
                    <Icon name="users" class="h-16 w-16 text-muted-foreground" />
                    <p class="text-center text-muted-foreground">
                        Nenhum cliente encontrado.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>