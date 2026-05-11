<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Pencil, Trash2 } from 'lucide-vue-next';
import vendedores from '@/routes/vendedores';
import { toast } from 'vue-sonner';

const props = defineProps<{
    vendedor: Record<string, any>;
}>();
const vendedor = props.vendedor;

function RemoverVendedor() {
    router.delete(vendedores.remove(vendedor.id_vendedor), {
        onSuccess: () => {
            toast.success('Salvado com sucesso');
        },
        onError: () => {
            toast.error('Erro ao salvar');
        },
    });
}
</script>

<template>
    <div class="flex gap-2">
        <Link :href="vendedores.persistir(vendedor.id_vendedor)">
            <Button class="bg-yellow-400 hover:bg-yellow-400/90 flex items-center gap-2">
                <Pencil class="h-4 w-4" />
                Editar Vendedor
            </Button>
        </Link>

        <!-- Botão Remover com Ícone -->
        <Dialog>
            <DialogTrigger>
                <Button class="bg-red-700 hover:bg-red-700/90 flex items-center gap-2">
                    <Trash2 class="h-4 w-4" />
                    Remover
                </Button>
            </DialogTrigger>

            <DialogContent>
                <span>
                    Tem certeza que deseja remover o vendedor:
                    <br />
                    {{ vendedor.users.name }}
                </span>

                <div class="flex justify-end gap-2">
                    <Button @click.prevent="RemoverVendedor"
                        class="bg-red-700 text-white hover:bg-red-700/90 flex items-center gap-2">
                        <Trash2 class="h-4 w-4" />
                        Remover Vendedor
                    </Button>

                    <DialogClose>
                        <Button>Cancelar</Button>
                    </DialogClose>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>