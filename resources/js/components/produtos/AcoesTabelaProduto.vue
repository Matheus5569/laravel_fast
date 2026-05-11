<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog';
import produtos from '@/routes/produto';
import { router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import ButtonCriarProduto from './ButtonCriarProduto.vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    produto: Record<string, any>;
}>();
function removerProduto() {
    router.delete(produtos.remove(props.produto.id), {
        onSuccess: () => {
                toast.success('Salvado com sucesso');
        },
        onError: () => {
                toast.success('Erro ao salvar');
        },
    });
}

</script>

<template>
    <div class="flex gap-2">
        <!-- Botão Editar -->


        <ButtonCriarProduto :produto="produto" :idproduto="produto.id" />

        <Dialog>
            <DialogTrigger as-child>
                <Button class="bg-red-700 hover:bg-red-700/90 flex items-center gap-1">
                    <Trash2 class="h-4 w-4" />
                    <span> Remover</span>
                </Button>
            </DialogTrigger>

            <DialogContent class="sm:max-w-md">
                <span>
                    Tem certeza que deseja remover o produto:
                    <br />
                    <strong>{{ produto.nome }}</strong>
                </span>

                <div class="flex justify-end gap-2 mt-4">
                    <Button @click.prevent="removerProduto"
                        class="bg-red-700 text-white hover:bg-red-700/90 flex items-center gap-1">
                        <Trash2 class="h-4 w-4" />
                        Remover Produto
                    </Button>

                    <DialogClose as-child>
                        <Button variant="outline">Cancelar</Button>
                    </DialogClose>
                </div>
            </DialogContent>
        </Dialog>

    </div>
</template>