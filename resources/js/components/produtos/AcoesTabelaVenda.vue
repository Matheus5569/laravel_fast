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

                <Heading title="Rastreamento da Produção" />

                <div v-if="dadosRastreamento">

                    <div v-for="item in dadosRastreamento.rastreamento" :key="item.id">
                        {{ item.status }}
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
