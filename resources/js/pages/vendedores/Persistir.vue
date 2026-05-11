<script setup lang="ts">
import { Form, Head, router, useForm, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import vendedores from '@/routes/vendedores';
import { onMounted, computed } from 'vue';
import { vMaska } from "maska/vue"
import { toast } from 'vue-sonner';
const page = usePage();
const props = page.props as unknown as {
    idVendedor?: number | null;
    vendedor?: Record<string, any>;
};
const vendedor = props.vendedor;
const id_vendedor = props.idVendedor;

// ===== TEXTOS DINÂMICOS  =====
const isEditing = computed(() => id_vendedor !== null && id_vendedor !== undefined);

const pageTitle = computed(() => isEditing.value ? 'Editar Vendedor' : 'Criar Vendedor');
const pageDescription = computed(() => isEditing.value ? 'Edite as informações do vendedor' : 'Preencha os dados para criar um novo vendedor');
const pessoalTitle = computed(() => isEditing.value ? 'Editar informações pessoais' : 'Informações pessoais');
const adicionalTitle = computed(() => isEditing.value ? 'Editar informações adicionais' : 'Informações adicionais');
const enderecoTitle = computed(() => isEditing.value ? 'Editar endereço' : 'Endereço');
const botaoTexto = computed(() => isEditing.value ? 'Atualizar Vendedor' : 'Salvar Vendedor');
// ===== FIM DOS TEXTOS DINÂMICOS =====

onMounted(() => {
    if (props.idVendedor && !vendedor) {
        router.visit(vendedores.listar().url);
    }
});


        
const form = useForm({
    id_vendedor: id_vendedor ?? null,
    nome: vendedor?.users?.name ?? '',
    email: vendedor?.users?.email ?? '',//user cuidado com o erro
    cpf: vendedor?.cpf ?? '',
    comissao: vendedor?.comissao ?? '',
    cep: vendedor?.endereco?.cep ?? '',
    rua: vendedor?.endereco?.rua ?? '',
    numero: vendedor?.endereco?.numero ?? '',
    complemento: vendedor?.endereco?.complemento ?? '',
    bairro: vendedor?.endereco?.bairro ?? '',
    cidade: vendedor?.endereco?.cidade ?? '',
    estado: vendedor?.endereco?.estado ?? '',
});

function onComissaoInput(e: Event) {
    const el = e.target as HTMLInputElement;
    let v = el.value;

    // aceita vírgula como separador decimal
    v = v.replace(',', '.');

    // apenas números e ponto
    v = v.replace(/[^\d.]/g, '');

    // apenas UM ponto
    const dotIndex = v.indexOf('.');
    if (dotIndex !== -1) {
        v = v.slice(0, dotIndex + 1) + v.slice(dotIndex + 1).replace(/\./g, '');
    }

    // se começa com ponto, prefixa 0
    if (v.startsWith('.')) {
        v = '0' + v;
    }

    // limita a 1 casa decimal
    if (v.includes('.')) {
        const [intPart, decPart] = v.split('.');
        v = intPart + '.' + decPart.slice(0, 1);
    }

    // bloqueia valores acima de 100
    const n = Number(v);
    if (!isNaN(n) && n > 100) {
        v = '100';
    }

    // se for exatamente 100, não permite ponto
    if (v === '100.' || v.startsWith('100.')) {
        v = '100';
    }

    form.comissao = v;
}

function onComissaoBlur() {
    if (!form.comissao) return;

    let n = Number(form.comissao.replace(',', '.'));

    if (isNaN(n)) {
        form.comissao = '';
        return;
    }

    // clamp final de segurança
    n = Math.max(0, Math.min(100, n));

    // remove .0 desnecessário
    form.comissao = Number.isInteger(n) ? String(n) : n.toFixed(1);
}

function viaCepLookup(cep: string) {
    cep = cep.replace(/\D/g, ''); // Remove non-digit characters
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
    if (id_vendedor !== null) {
        form.put(vendedores.update(id_vendedor).url, {
            onSuccess: () => {
                toast.success('Salvado com sucesso');
            },
            onError: () => {
                toast.error('Erro ao salvar');
            },
        });
        return;
    }
    form.post(vendedores.create().url, {
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
    <!-- Título dinâmico na aba do navegador -->

    <Head :title="pageTitle" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Heading com título e descrição dinâmicos -->
            <Heading :title="pageTitle" :description="pageDescription" />

            <div class="md:grid-cols 4 grid-cols-1">
                <div
                    class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">

                    <!-- Loading  -->
                    <div v-if="isEditing && !vendedor" class="flex justify-center items-center py-20">
                        <div class="loader"></div>
                        <p class="ml-3">Carregando dados do vendedor...</p>
                    </div>

                    <Form v-else @submit.prevent="enviar">
                        <Heading :title="pessoalTitle" />

                        <div class="mb-4 grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="grid gap-2">
                                <Label for="nome">Nome</Label>
                                <Input v-model="form.nome" id="nome" type="text" autofocus :tabindex="1"
                                    autocomplete="nome" name="nome" placeholder="Nome Completo" />
                                <InputError :message="form.errors.nome" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input id="email" v-model="form.email" type="email" :tabindex="2" autocomplete="email"
                                    name="email" placeholder="email@exemplo.com" />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="cpf">CPF</Label>
                                <Input v-model="form.cpf" id="cpf" type="text" :tabindex="2" autocomplete="cpf"
                                    name="cpf" placeholder="000.000.000-00" v-maska ="'###.###.###-##'" />
                                <InputError :message="form.errors.cpf" />
                            </div>
                        </div>

                        <Separator />

                        <!-- Título dinâmico da seção -->
                        <Heading :title="adicionalTitle" class="mt-4" />
                        <div class="mt-4 mb-4 grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="grid gap-2">
                                <Label for="comissao">Comissão</Label>
                                <div class="relative">
                                    <Input v-model="form.comissao" type="text" class="pr-8" @blur="onComissaoBlur"
                                        @input="onComissaoInput" id="comissao" placeholder="0.00%" />
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-muted-foreground">
                                        %
                                    </span>
                                </div>
                                <InputError :message="form.errors.comissao" />
                            </div>
                        </div>

                        <Separator />

                        <Heading :title="enderecoTitle" class="mt-4" />
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
                                    name="numero" placeholder="Insira sua numero" />
                                <InputError :message="form.errors.numero" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="complemento">Complemento</Label>
                                <Input v-model="form.complemento" id="complemento" type="text" :tabindex="2"
                                    autocomplete="complemento" name="complemento"
                                    placeholder="Insira sua complemento" />
                                <InputError :message="form.errors.complemento" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="bairro">Bairro</Label>
                                <Input v-model="form.bairro" id="bairro" type="text" :tabindex="2" autocomplete="bairro"
                                    name="bairro" placeholder="Insira sua bairro" />
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
                                    name="estado" placeholder="Insira sua estado" />
                                <InputError :message="form.errors.estado" />
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-5 ">
                            <Button type="submit"
                                class="mt-2 w-full bg-yellow-400 text-black hover:bg-yellow-500 flex items-center justify-center gap-2"
                                tabindex="5" data-test="register-user-button">
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