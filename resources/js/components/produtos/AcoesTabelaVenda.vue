<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

import Heading from '@/components/Heading.vue';
import Icon from '@/components/Icon.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog';
import vendas from '@/routes/vendas';
import { ref } from 'vue';
import { Helper } from '@/Utils/Helper';

const props = defineProps<{
    venda: Record<string, any>;
}>();

const venda = props.venda;



const modalRastreamento = ref(false);
const dadosRastreamento = ref<any>(null);

async function abrirRastreamento() {

    const response = await fetch(
        `/rastreamento/${venda.qr_code}`
    );

    dadosRastreamento.value =
        await response.json();

    modalRastreamento.value = true;
}

function removeVenda(id_venda: number) {
    router.delete(vendas.remove(id_venda), {
        onSuccess: () => {
            toast.success('Venda removida com sucesso!');
        },
        onError: (errors) => {
            toast.error('Erro ao remover a venda.');
            console.error(errors);
        },
    });
}
function gerarExcel(idVenda) {
    router.get(vendas.export(idVenda));
}
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

const etapas = [
    'orcamento_criado',
    'fazendo_materiais',
    'recorte',
    'pintura',
    'estufa_polimerizacao',
    'separacao',
    'aguardando_caminhoneiro',
    'concluido',
];

function indiceStatusAtual() {
    if (!dadosRastreamento.value) {
        return -1;
    }

    return etapas.indexOf(
        dadosRastreamento.value.status_atual
    );
}
function obterDataEtapa(status: string) {

    if (!dadosRastreamento.value) {
        return null;
    }

    const registro =
        dadosRastreamento.value.rastreamento.find(
            (item: any) => item.status === status
        );

    return registro?.created_at ?? null;
}
</script>

<template>
    <div class="flex gap-2">
        <Link :href="vendas.persistir(venda.id)">
            <Button class="bg-yellow-400 text-black hover:bg-yellow-500">
                <Icon :name="venda ? 'pencil' : 'plus'" />
                Editar Venda
            </Button>
        </Link>
        <Dialog>
            <DialogTrigger>
                <Button class="bg-destructive text-black hover:bg-destructive/90">
                    <Icon name="trash" />
                    Remover
                </Button>
            </DialogTrigger>
            <DialogContent>
                <div class="flex flex-col gap-4">
                    <Heading :title="`Confirma a exclusao da venda?`" />
                    <div class="grid-cols-1 md:grid-cols-2">
                        <!-- Form fields for creating a new vendedor go here -->
                        <div class="mt-4 flex justify-end gap-2">
                            <Button @click="removeVenda(venda.id)"
                                class="mt-2 bg-destructive text-white hover:bg-destructive/90" tabindex="5"
                                data-test="register-user-button">
                                Confirmar
                            </Button>
                            <DialogClose as-child>
                                <Button type="button" class="mt-2" tabindex="5" data-test="register-user-button">
                                    Cancelar
                                </Button>
                            </DialogClose>
                        </div>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
        <Dialog v-model:open="modalRastreamento">

            <DialogContent>


                <div v-if="dadosRastreamento">

                    <Heading title="Rastreamento da Produção" />
                    <h3>Status Atual:</h3>

                    <p>
                        {{ traduzirStatus(dadosRastreamento.status_atual) }}
                    </p>

                    <hr class="my-4">

                    <div class="mt-4">

                        <div v-for="(etapa, index) in etapas" :key="etapa" class="flex gap-4">

                            <!-- Coluna da bolinha -->

                            <div class="flex flex-col items-center">

                                <div v-if="index < indiceStatusAtual()"
                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-green-500 text-white">
                                    ✓
                                </div>

                                <div v-else-if="index === indiceStatusAtual()"
                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-500 text-white">
                                    ●
                                </div>

                                <div v-else
                                    class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-gray-300 text-gray-400">
                                    ○
                                </div>

                                <div v-if="index < etapas.length - 1" class="h-10 w-[2px] bg-gray-300" />
                            </div>


                            <div class="pb-8">
                                <p :class="{
                                    'font-bold text-green-600': index < indiceStatusAtual(),
                                    'font-bold text-blue-600': index === indiceStatusAtual(),
                                    'text-gray-400': index > indiceStatusAtual(),
                                }">
                                    {{ traduzirStatus(etapa) }}
                                </p>

                                <p v-if="obterDataEtapa(etapa)" class="text-xs text-muted-foreground">
                                    {{
                                        Helper.formatarDateTimePtBr(
                                            obterDataEtapa(etapa)
                                        )
                                    }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </DialogContent>

        </Dialog>
        <Button class="bg-blue-500 hover:bg-blue-600" @click="abrirRastreamento">
            <Icon name="eye" />
            Rastrear
        </Button>
        <a :href="vendas.export(venda.id).url" target="_blank" rel="noopener noreferrer">
            <Button class="bg-green-800 hover:bg-green-800/90">
                <Icon name="sheet" />
                Exportar em Excel
            </Button>
        </a>
        <a :href="vendas.exportPdf(venda.id).url" target="_blank" rel="noopener noreferrer">
            <Button class="bg-red-400 hover:bg-red-400/90">
                <Icon name="fileText" />
                Exportar em PDF
            </Button>
        </a>
    </div>
</template>

<style scoped></style>
