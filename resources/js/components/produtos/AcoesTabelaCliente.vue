<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
// import { toast } from 'vue-sonner';

import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog';
import clientes from '@/routes/clientes';
import { Pencil, Trash2 } from 'lucide-vue-next';  
import { toast } from 'vue-sonner';

const props = defineProps<{
    cliente: Record<string, any>;
}>();
const cliente = props.cliente;

function RemoverCliente() {
    router.delete(clientes.remove(cliente.id), {
        onSuccess: () => {
            toast.success('Salvado com sucesso');
            // toast.success('Cliente removido com sucesso');
        },
        onError: (errors) => {
            toast.error('Erro ao salvar');
            // toast.error('Erro ao remover cliente');
        },
    });
}
</script>

<template>
    <div class="flex gap-2">
        <!-- Botão Editar -->
        <Link :href="clientes.persistir(cliente.id)">
            <Button class="bg-yellow-400 hover:bg-yellow-400/90">
                <Pencil class="h-4 w-4" />
                Editar Cliente
            </Button>
        </Link>

        <!-- Botão Remover com Dialog -->
        <Dialog>
            <DialogTrigger>
                <Button class="bg-red-700 hover:bg-red-700/90">
                    <Trash2 class="h-4 w-4" />
                    Remover
                </Button>
            </DialogTrigger>

            <DialogContent>
                <span>
                    Tem certeza que deseja remover o cliente:
                    <br />
                    <strong>{{ cliente.nome }}</strong>
                </span>

                <div class="flex justify-end gap-2">
                    <Button @click.prevent="RemoverCliente" class="bg-red-700 text-white hover:bg-red-700/90">

                        Remover Cliente
                    </Button>

                    <DialogClose>
                        <Button>Cancelar</Button>
                    </DialogClose>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>

<style scoped></style>