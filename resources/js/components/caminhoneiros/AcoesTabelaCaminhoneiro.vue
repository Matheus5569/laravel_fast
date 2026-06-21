<script setup lang="ts">
import { router } from '@inertiajs/vue3';

import {
    Dialog,
    DialogContent,
    DialogTrigger,
    DialogClose,
} from '@/components/ui/dialog';

import { Button } from '@/components/ui/button';

import { Trash2 } from 'lucide-vue-next';

import { toast } from 'vue-sonner';

import caminhoneiros from '@/routes/caminhoneiros';

import { Pencil } from 'lucide-vue-next';

const props = defineProps<{
    caminhoneiro: Record<string, any>;
}>();

function removerCaminhoneiro() {

    router.delete(
        caminhoneiros.remove(props.caminhoneiro.id),
        {
            onSuccess: () => {
                toast.success(
                    'Caminhoneiro removido'
                );
            },

            onError: () => {
                toast.error(
                    'Erro ao remover'
                );
            },
        }
    );
}
</script>

<template>

    <div class="flex gap-2">

        <Button
            as-child
            class="bg-yellow-400 text-black hover:bg-yellow-500"
        >
            <a :href="caminhoneiros.persistir(props.caminhoneiro.id).url">
                <Pencil class="h-4 w-4 mr-2" />
                Editar Caminhoneiro
            </a>
        </Button>

        <Dialog>

            <DialogTrigger as-child>

                <Button
                    class="bg-red-700 hover:bg-red-700/90"
                >
                    <Trash2 class="h-4 w-4" />
                    Remover
                </Button>

            </DialogTrigger>

            <DialogContent>

                <p>
                    Deseja remover:
                    <strong>
                        {{ caminhoneiro.nome }}
                    </strong>
                    ?
                </p>

                <div class="flex justify-end gap-2">

                    <Button
                        @click.prevent="removerCaminhoneiro"
                        class="bg-red-700"
                    >
                        Remover
                    </Button>

                    <DialogClose as-child>

                        <Button variant="outline">
                            Cancelar
                        </Button>

                    </DialogClose>

                </div>

            </DialogContent>

        </Dialog>

    </div>

</template>