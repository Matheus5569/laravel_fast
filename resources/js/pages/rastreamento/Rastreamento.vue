<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';

import { Button } from '@/components/ui/button';
import { computed, ref } from 'vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

const page = usePage();
const filtro = ref('');
const vendas = page.props.vendas as any[];

function avancarEtapa(idVenda: number) {
    router.post(`/rastreamento/avancar/${idVenda}`);
}
const vendasFiltradas = computed(() => {

    if (!filtro.value) {
        return vendas;
    }

    return vendas.filter((venda) => {

        const nomeCliente =
            venda.cliente?.nome?.toLowerCase() ?? '';

        const codigo =
            venda.qr_code?.toLowerCase() ?? '';

        const pesquisa =
            filtro.value.toLowerCase();

        return (
            nomeCliente.includes(pesquisa) ||
            codigo.includes(pesquisa)
        );
    });

});
function traduzirStatus(status: string) {
    const statusMap = {
        orcamento_criado: 'Orçamento Criado',
        fazendo_materiais: 'Fazendo Materiais',
        recorte: 'Recorte',
        pintura: 'Pintura',
        estufa_polimerizacao: 'Estufa',
        separacao: 'Separação',
        aguardando_caminhoneiro: 'Aguardando Caminhoneiro',
        concluido: 'Concluído',
    };

    return statusMap[status] ?? status;
}
function pesquisar() {
    router.get('/rastreamento', {
        busca: filtro.value,
    });
}
</script>
<template>

    <AppLayout>

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <Heading title="Rastreamentos" description="Controle de produção" />

            <div
                class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
                <div class="mb-4">
                    <input v-model="filtro" type="text" placeholder="Pesquisar cliente ou código"
                        class="w-full rounded border p-2">
                </div>
                <Table>

                    <TableHeader>

                        <TableRow>
                            <TableHead>Código</TableHead>
                            <TableHead>Cliente</TableHead>
                            <TableHead>Status Atual</TableHead>
                            <TableHead>Ações</TableHead>
                        </TableRow>

                    </TableHeader>

                    <TableBody>

                        <TableRow v-for="venda in vendasFiltradas" :key="venda.id">
                            <TableCell>
                                PED-{{ String(venda.id).padStart(5, '0') }}
                            </TableCell>
                            <TableCell>
                                {{ venda.cliente.nome }}
                            </TableCell>

                            <TableCell>
                                {{ traduzirStatus(venda.status_atual) }}
                            </TableCell>

                            <TableCell>

                                <Button class="bg-yellow-400 text-black hover:bg-yellow-500"
                                    @click="avancarEtapa(venda.id)" :disabled="venda.status_atual === 'concluido'">
                                    {{
                                        venda.status_atual === 'concluido'
                                            ? 'Concluído'
                                            : 'Avançar Etapa'
                                    }}
                                </Button>

                            </TableCell>

                        </TableRow>

                    </TableBody>

                </Table>

            </div>

        </div>

    </AppLayout>

</template>