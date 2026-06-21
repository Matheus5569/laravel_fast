<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Icon from '@/components/Icon.vue';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

import { computed } from 'vue';

import ButtonCriarBaia from '@/components/baias/ButtonCriarBaia.vue';
import AcoesTabelaBaia from '@/components/baias/AcoesTabelaBaia.vue';

type Baia = Record<string, any>;

const page = usePage();

const baiasList = computed<Baia[]>(() => {
    return page.props.baias ?? [];
});

// Para as baias aparecer com acento no front

function traduzirStatus(status: string) {

    const statusMap: Record<string, string> = {
        Livre: 'Livre',
        Ocupada: 'Ocupada',
        Manutencao: 'Manutenção',
    };

    return statusMap[status] ?? status;
}

</script>

<template>

    <Head title="Baias" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <Heading title="Baias" description="Lista de baias cadastradas" />

            <!-- BOTÃO CRIAR -->
            <div class="md:grid-cols-4 grid-cols-1">
                <ButtonCriarBaia />
            </div>

            <!-- TABELA -->
            <div
                class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">

                <Table v-if="baiasList.length > 0">
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Nome</TableHead>
                            <TableHead>Descrição</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Ações</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>

                        <TableRow v-for="baia in baiasList" :key="baia.id">

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
                                <span :class="{
                                    'text-green-600 font-semibold': baia.status === 'Livre',
                                    'text-red-600 font-semibold': baia.status === 'Ocupada',
                                    'text-yellow-600 font-semibold': baia.status === 'Manutencao',
                                }">
                                    {{ traduzirStatus(baia.status) }}
                                </span>
                            </TableCell>

                            <TableCell>
                                <AcoesTabelaBaia :baia="baia" />
                            </TableCell>

                        </TableRow>

                    </TableBody>
                </Table>

                <!-- SEM REGISTROS -->
                <div v-else class="flex h-full w-full flex-col items-center justify-center gap-4">

                    <Icon name="grid" class="h-16 w-16 text-muted-foreground" />

                    <p class="text-center text-muted-foreground">
                        Nenhuma baia encontrada.
                    </p>

                </div>

            </div>

        </div>
    </AppLayout>
</template>