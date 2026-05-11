<script setup lang="ts">
import { Form, Head, router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import clientes from '@/routes/clientes';
import { toast } from 'vue-sonner';
import { vMaska } from "maska/vue";

const page = usePage();
const props = page.props as unknown as {
    idcliente?: number | null;
    cliente?: Record<string, any>;
};

const cliente = props.cliente;
const id_cliente = props.idcliente;

// ===== TEXTOS DINÂMICOS =====
const isEditing = computed(() => id_cliente !== null && id_cliente !== undefined);
const pageTitle = computed(() => isEditing.value ? 'Editar Cliente' : 'Criar Cliente');
const pageDescription = computed(() =>
    isEditing.value ? 'Edite as informações do cliente' : 'Preencha os dados para criar um novo cliente'
);
const botaoTexto = computed(() => isEditing.value ? 'Atualizar Cliente' : 'Criar Cliente');

onMounted(() => {
    if (props.idcliente != null && !cliente) {
        router.visit(clientes.listar().url);
    }
});
console.log(useForm);
const form = useForm({
    id_cliente: id_cliente ?? null,
    nome: cliente?.nome ?? '',
    email: cliente?.email ?? '',
    cep: cliente?.endereco?.cep ?? '',
    rua: cliente?.endereco?.rua ?? '',
    numero: cliente?.endereco?.numero ?? '',
    complemento: cliente?.endereco?.complemento ?? '',
    bairro: cliente?.endereco?.bairro ?? '',
    cidade: cliente?.endereco?.cidade ?? '',
    estado: cliente?.endereco?.estado ?? '',
});

function viaCepLookup(cep: string) {
    cep = cep.replace(/\D/g, '');
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then((response) => response.json())
        .then((data) => {
            form.rua = data.logradouro || '';
            form.bairro = data.bairro || '';
            form.cidade = data.localidade || '';
            form.estado = data.uf || '';
        })
        .catch((error) => {
            console.error('Erro ao buscar CEP:', error);
        });
}

function enviar() {
    if (id_cliente !== null) {
        form.put(clientes.update(id_cliente).url, {
            onSuccess: () => {
                toast.success('Salvado com sucesso');
            },
            onError: () => {
                toast.error('Erro ao salvar');
            },
        });
        return;
    }
    form.post(clientes.create().url, {
        onSuccess: () => {
            toast.success('Salvado com sucesso');
        },
        onError: () => {
            toast.error('Erro ao salvar');

        },
    });
    return;
}

</script>

<template>

    <Head :title="pageTitle" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Heading :title="pageTitle" :description="pageDescription" />

            <div class="md:grid-cols 4 grid-cols-1">
                <div
                    class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">

                    <div v-if="isEditing && !cliente" class="flex justify-center items-center py-20">
                        <div class="loader"></div>
                        <p class="ml-3">Carregando dados do cliente...</p>
                    </div>  

                    <Form v-else @submit.prevent="enviar">
                        <Heading title="Informações pessoais" />

                        <div class="mb-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="nome">Nome</Label>
                                <Input v-model="form.nome" id="nome" type="text" autofocus :tabindex="1"
                                    autocomplete="nome" nome="nome" placeholder="Nome Completo" />
                                <InputError :message="form.errors.nome" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input id="email" v-model="form.email" type="email" :tabindex="2" autocomplete="email"
                                    name="email" placeholder="email@exemplo.com" />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>

                        <Separator />

                        <Heading title="Endereço" class="mt-4" />
                        <div class="mt-4 mb-4 grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="grid gap-2">
                                <Label for="cep">CEP</Label>
                                <Input @blur="viaCepLookup(form.cep)" v-model="form.cep" id="cep" type="text"
                                    :tabindex="2" autocomplete="cep" name="cep" placeholder="insira seu CEP"
                                    v-maska="'#####-###'" />
                                <InputError :message="form.errors.cep" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="rua">Rua</Label>
                                <Input v-model="form.rua" id="rua" type="text" :tabindex="2" autocomplete="rua"
                                    name="rua" placeholder="Insira sua rua" />
                                <InputError :message="form.errors.rua" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="numero">Numero</Label>
                                <Input v-model="form.numero" id="numero" type="text" :tabindex="2" autocomplete="numero"
                                    name="numero" placeholder="Insira seu número" />
                                <InputError :message="form.errors.numero" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="complemento">Complemento</Label>
                                <Input v-model="form.complemento" id="complemento" type="text" :tabindex="2"
                                    autocomplete="complemento" name="complemento" placeholder="Complemento" />
                                <InputError :message="form.errors.complemento" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="bairro">Bairro</Label>
                                <Input v-model="form.bairro" id="bairro" type="text" :tabindex="2" autocomplete="bairro"
                                    name="bairro" placeholder="Insira seu bairro" />
                                <InputError :message="form.errors.bairro" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="cidade">Cidade</Label>
                                <Input v-model="form.cidade" id="cidade" type="text" :tabindex="2" autocomplete="cidade"
                                    name="cidade" placeholder="Insira sua cidade" />
                                <InputError :message="form.errors.cidade" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="estado">Estado</Label>
                                <Input v-model="form.estado" id="estado" type="text" :tabindex="2" autocomplete="estado"
                                    name="estado" placeholder="Insira seu estado" />
                                <InputError :message="form.errors.estado" />
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-5">
                            <Button type="submit" class="bg-yellow-400 text-black hover:bg-yellow-500" tabindex="5"
                                data-test="register-user-button">
                                {{ botaoTexto }}
                            </Button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>