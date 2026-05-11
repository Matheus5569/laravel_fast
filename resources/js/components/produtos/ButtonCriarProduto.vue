<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import produtos from '@/routes/produto';
import { toast } from 'vue-sonner';
import Icon from '../Icon.vue';

const page = usePage();

const props = defineProps<{
    idproduto?: number | null;
    produto?: Record<string, any>;
}>();

const produto = props.produto;
const id_produto = props.idproduto;


// ===== TEXTOS DINÂMICOS =====
const pageTitle = id_produto ? 'Editar Produto' : 'Criar Produto';

const botaoTexto = id_produto ? 'Editar Produto' : 'Criar Produto';

// ===== CONTROLE DA MODAL =====
const open = ref(false);

const form = useForm({
    id: produto?.id ?? '',
    nome: produto?.nome ?? '',
    preco: produto?.preco ?? '',
});

watch(() => props.produto, (novoProduto) => {
    if (novoProduto) {
        form.nome = novoProduto.nome;
        form.preco = novoProduto.preco;
    }
});


function enviar() {
    if (id_produto) {
        form.put(produtos.update(id_produto).url, {
            onSuccess: () => {
                open.value = false;
                toast.success('Salvado com sucesso');
            },
            onError: () => {
                console.log(form.errors);
                toast.error('Erro ao salvar');
            },
        });
        return;
    }

    form.post(produtos.create().url, {
        onSuccess: () => {
            toast.success('Salvado com sucesso');
        },
        onError: () => {
            toast.error('Erro ao salvar');
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
                <Icon :name="idproduto ? 'pencil' : 'plus'" />
                <!-- ^-Adiciona um icone adaptavel cê for editar ele coloca o pencil ce for o bottão de criar ele coloca o plus -->

                <span> {{ idproduto ? 'Editar Produto' : 'Criar Novo Produto' }} </span>
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-md">

            <Heading :title="pageTitle" class="mb-4" />

            <div class="grid gap-4 py-2">
                <div class="grid gap-2">
                    <Label for="nome">Nome do Produto</Label>
                    <Input id="nome" v-model="form.nome" type="text" placeholder="Coloque aqui o nome do produto"
                        autofocus />
                    <InputError :message="form.errors.nome" />
                </div>

                <div class="grid gap-2">
                    <Label for="preco">Preço</Label>
                    <Input id="preco" v-model="form.preco" type="text" placeholder="Coloque aqui o preço do produto" />
                    <InputError :message="form.errors.preco" />
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <Button @click.prevent="enviar" type="submit" class="bg-yellow-400 text-black hover:bg-yellow-500"
                    :disabled="form.processing">
                    {{ id_produto ? 'Editar' : 'Criar' }}
                </Button>

                <DialogClose as-child>
                    <Button type="button" variant="outline" @click="fecharModal">
                        Cancelar
                    </Button>
                </DialogClose>
            </div>

        </DialogContent>
    </Dialog>
</template>