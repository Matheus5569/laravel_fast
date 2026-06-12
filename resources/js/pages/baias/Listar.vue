<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import baias from '@/routes/baias';
import { computed } from 'vue';
import Icon from '@/components/Icon.vue';

type Baia = Record<string, any>;

const page = usePage();

const baiasList = computed<Baia[]>(() => {
    return page.props.baias ?? [];
});

console.log(baiasList);
</script>

<template>
    <Head title="Baias" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <Heading
                title="Baias"
                description="Lista de baias"
            />

            <div class="md:grid-cols-4 grid-cols-1">
                <Link :href="baias.listar()">
                    <Button class="bg-yellow-400 text-black hover:bg-yellow-500">
                        <Icon name="plus" />
                        Criar Nova Baia
                    </Button>
                </Link>
            </div>

            <div
                class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">

                <Table v-if="baiasList.length > 0">
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Nome</TableHead>
                            <TableHead>Descrição</TableHead>
                            <TableHead>Status</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow
                            v-for="baia in baiasList"
                            :key="baia.id"
                        >
                            <TableCell>
                                {{ baia.id }}
                            </TableCell>

                            <TableCell>
                                {{ baia.nome }}
                            </TableCell>

                            <TableCell>
                                {{ baia.descricao }}
                            </TableCell>

                            <TableCell>
                                {{ baia.status }}
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <div
                    class="flex h-full w-full flex-col items-center justify-center gap-4"
                    v-else
                >
                    <Icon
                        name="grid"
                        class="h-16 w-16 text-muted-foreground"
                    />

                    <p class="text-center text-muted-foreground">
                        Nenhuma baia encontrada.
                    </p>
                </div>

            </div>
        </div>
    </AppLayout>
</template>