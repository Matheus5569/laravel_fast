<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import Icon from '@/components/Icon.vue';

import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import baias from '@/routes/baias';

import { toast } from 'vue-sonner';

const props = defineProps<{
    idbaia?: number | null;
    baia?: Record<string, any>;
}>();

const baia = props.baia;
const id_baia = props.idbaia;

const open = ref(false);

const pageTitle = id_baia
    ? 'Editar Baia'
    : 'Criar Baia';

const form = useForm({
    id: baia?.id ?? '',
    nome: baia?.nome ?? '',
    descricao: baia?.descricao ?? '',
    status: baia?.status ?? 'Livre',
});

watch(
    () => props.baia,
    (novaBaia) => {
        if (novaBaia) {
            form.nome = novaBaia.nome;
            form.descricao = novaBaia.descricao;
            form.status = novaBaia.status;
        }
    }
);

function enviar() {

    if (id_baia) {

        form.put(baias.update(id_baia).url, {

            onSuccess: () => {
                open.value = false;
                toast.success('Baia atualizada');
            },

            onError: () => {
                toast.error('Erro ao atualizar');
            },
        });

        return;
    }

    form.post(baias.create().url, {

        onSuccess: () => {

            open.value = false;
            form.reset();

            toast.success('Baia criada');
        },

        onError: () => {
            toast.error('Erro ao criar');
        },
    });
}

function fecharModal() {
    open.value = false;
    form.reset();
}
</script>

<template>

    <Dialog v-model:open="open">

        <DialogTrigger as-child>

            <Button class="bg-yellow-400 text-black hover:bg-yellow-500">

                <Icon :name="idbaia ? 'pencil' : 'plus'" />

                <span>
                    {{ idbaia ? 'Editar Baia' : 'Criar Nova Baia' }}
                </span>

            </Button>

        </DialogTrigger>

        <DialogContent class="sm:max-w-md">

            <Heading :title="pageTitle" />

            <div class="grid gap-4">

                <div>
                    <Label>Nome</Label>

                    <Input v-model="form.nome" placeholder="Nome da baia" />

                    <InputError :message="form.errors.nome" />
                </div>

                <div>
                    <Label>Descrição</Label>

                    <Input v-model="form.descricao" placeholder="Descrição" />

                    <InputError :message="form.errors.descricao" />
                </div>

                <div>
                    <Label>Status</Label>

                    <select v-model="form.status" class="border rounded-md px-3 py-2 w-full">
                        <option value="Livre">Livre</option>
                        <option value="Ocupada">Ocupada</option>
                        <option value="Manutencao">Manutenção</option>
                    </select>

                    <InputError :message="form.errors.status" />
                </div>

            </div>

            <div class="flex justify-end gap-2 mt-4">

                <Button @click.prevent="enviar" class="bg-yellow-400 text-black">
                    {{ idbaia ? 'Editar' : 'Criar' }}
                </Button>

                <DialogClose as-child>
                    <Button variant="outline" @click="fecharModal">
                        Cancelar
                    </Button>
                </DialogClose>

            </div>

        </DialogContent>

    </Dialog>

</template>