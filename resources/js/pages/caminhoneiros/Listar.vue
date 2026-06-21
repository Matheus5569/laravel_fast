<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import Icon from '@/components/Icon.vue';

import AppLayout from '@/layouts/AppLayout.vue';

import caminhoneiros from '@/routes/caminhoneiros';

import AcoesTabelaCaminhoneiro from '@/components/caminhoneiros/AcoesTabelaCaminhoneiro.vue';

import { Button } from '@/components/ui/button';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

import { computed } from 'vue';

type Caminhoneiro = Record<string, any>;

const page = usePage();

const caminhoneirosList = computed<Caminhoneiro[]>(() => {
    return page.props.caminhoneiros ?? [];
});

function traduzirStatus(status: string) {

    const statusMap: Record<string, string> = {
        Livre: 'Livre',
        EmViagem: 'Em Viagem',
        Folga: 'Folga',
    };

    return statusMap[status] ?? status;
}
</script>

<template>

    <Head title="Caminhoneiros" />

    <AppLayout>

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <Heading
                title="Caminhoneiros"
                description="Lista de caminhoneiros cadastrados"
            />

            <div class="md:grid-cols-4 grid-cols-1">

                <Link :href="caminhoneiros.persistir()">

                    <Button class="bg-yellow-400 text-black hover:bg-yellow-500">

                        <Icon name="plus" />

                        Criar Novo Caminhoneiro

                    </Button>

                </Link>

            </div>

            <div
                class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4"
            >

                <Table v-if="caminhoneirosList.length > 0">

                    <TableHeader>

                        <TableRow>

                            <TableHead>Nome</TableHead>

                            <TableHead>CPF</TableHead>

                            <TableHead>Telefone</TableHead>

                            <TableHead>Placa</TableHead>

                            <TableHead>Status</TableHead>

                            <TableHead>Ações</TableHead>

                        </TableRow>

                    </TableHeader>

                    <TableBody>

                        <TableRow
                            v-for="caminhoneiro in caminhoneirosList"
                            :key="caminhoneiro.id"
                        >

                            <TableCell>
                                {{ caminhoneiro.nome }}
                            </TableCell>

                            <TableCell>
                                {{ caminhoneiro.cpf }}
                            </TableCell>

                            <TableCell>
                                {{ caminhoneiro.telefone }}
                            </TableCell>

                            <TableCell>
                                {{ caminhoneiro.placa_caminhao }}
                            </TableCell>

                            <TableCell>

                                <span
                                    :class="{
                                        'text-green-600 font-semibold':
                                            caminhoneiro.status === 'Livre',

                                        'text-blue-600 font-semibold':
                                            caminhoneiro.status === 'EmViagem',

                                        'text-yellow-600 font-semibold':
                                            caminhoneiro.status === 'Folga',
                                    }"
                                >
                                    {{ traduzirStatus(caminhoneiro.status) }}
                                </span>

                            </TableCell>

                            <TableCell>

                                <AcoesTabelaCaminhoneiro
                                    :caminhoneiro="caminhoneiro"
                                />

                            </TableCell>

                        </TableRow>

                    </TableBody>

                </Table>

                <div
                    v-else
                    class="flex h-full w-full flex-col items-center justify-center gap-4 py-20"
                >

                    <Icon
                        name="truck"
                        class="h-16 w-16 text-muted-foreground"
                    />

                    <p class="text-center text-muted-foreground">
                        Nenhum caminhoneiro encontrado.
                    </p>

                </div>

            </div>

        </div>

    </AppLayout>

</template>