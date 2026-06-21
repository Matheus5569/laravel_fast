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

import baias from '@/routes/baias';

import ButtonCriarBaia from './ButtonCriarBaia.vue';

const props = defineProps<{
    baia: Record<string, any>;
}>();

function removerBaia() {

    router.delete(
        baias.remove(props.baia.id),

        {
            onSuccess: () => {
                toast.success('Baia removida');
            },

            onError: () => {
                toast.error('Erro ao remover');
            },
        }
    );
}
</script>

<template>

    <div class="flex gap-2">

        <ButtonCriarBaia
            :baia="baia"
            :idbaia="baia.id"
        />

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
                    <strong>{{ baia.nome }}</strong> ?
                </p>

                <div class="flex justify-end gap-2">

                    <Button
                        @click.prevent="removerBaia"
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