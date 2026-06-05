<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Pencil, Trash2 } from 'lucide-vue-next'
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import vendedores from '@/routes/vendedores';
import { computed } from 'vue';
import { Helper } from '@/Utils/Helper';
import AcoesTabelaVendedor from '@/components/produtos/AcoesTabelaVendedor.vue';
import Icon from '@/components/Icon.vue';

type Vendedor = Record<string, any>;
const page = usePage();
const vendedoresList = computed<Vendedor[]>(() => {
    return page.props.vendedores ?? [];
});
console.log(vendedoresList);
</script>

<template>

    <Head title="Gerente" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Heading title="Vendedores" description="Lista de vendedores" />
            <div class="md:grid-cols 4 grid-cols-1">
                <Link :href="vendedores.persistir()">
                    <Button class="bg-yellow-400 text-black hover:bg-yellow-500">
                        <Icon name="plus" />
                        Criar Novo Gerente
                    </Button>
                </Link> 
            </div>

            <div
                class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
                <!-- <Table v-if="vendedoresList.length > 0"> -->
                <Table v-if="vendedoresList.length > 0">
                    <TableHeader>
                        <TableRow>
                            <TableHead> Nome </TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>CPF</TableHead>
                            <TableHead> Ações </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <!-- <TableRow v-for="v in vendedoresList" :key="v.id">
                                            <TableCell> {{ v.user.name ?? '—' }} </TableCell>
                                            <TableCell> {{ v.user.email ?? '—' }} </TableCell>
                                            <TableCell>
                                                {{ Helper.formatarCPF(v.cpf) ?? '—' }}
                                            </TableCell>
                                        <TableCell>
                                             <AcoesTabelaVendedor :v="v" />
                                        </TableCell> -->
                        <!-- </TableRow> -->
                        <TableRow v-for="vendedor in vendedoresList" :key="vendedor.id_vendedor">
                            <TableCell> {{ vendedor.users.name }}</TableCell>
                            <TableCell> {{ vendedor.users.email }} </TableCell>
                            <TableCell>{{ Helper.formatarCPF(vendedor.cpf) }} </TableCell>
                            <TableCell>
                                <div class="flex gap-4">
                                    <div class="flex gap-4">
                                        <!-- Editar -->
                                        
                                     <AcoesTabelaVendedor :vendedor="vendedor"/>
                                        
                                    </div>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>

                </Table>
                
              <div class="flex h-full w-full flex-col items-center justify-center gap-4" v-else>
                    <Icon name="users" class="h-16 w-16 text-muted-foreground" />
                     <p class="text-center text-muted-foreground">
                        Nenhum Gerente encontrado.
                    </p> 
                </div> 
            </div>
        </div>
    </AppLayout>
</template>
