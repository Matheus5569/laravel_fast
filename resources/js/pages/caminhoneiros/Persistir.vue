<script setup lang="ts">
import { Form, Head, useForm, usePage } from '@inertiajs/vue3';

import { computed } from 'vue';

import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';

import AppLayout from '@/layouts/AppLayout.vue';

import caminhoneiros from '@/routes/caminhoneiros';

import { toast } from 'vue-sonner';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Pencil, Plus } from 'lucide-vue-next';

const page = usePage();

const props = page.props as unknown as {
    idcaminhoneiro?: number | null;
    caminhoneiro?: Record<string, any>;
};

const caminhoneiro = props.caminhoneiro;

const id_caminhoneiro = props.idcaminhoneiro;

const isEditing = computed(() =>
    id_caminhoneiro !== null &&
    id_caminhoneiro !== undefined
);

const pageTitle = computed(() =>
    isEditing.value
        ? 'Editar Caminhoneiro'
        : 'Criar Caminhoneiro'
);

const pageDescription = computed(() =>
    isEditing.value
        ? 'Edite as informações do caminhoneiro'
        : 'Preencha os dados do caminhoneiro'
);

const botaoTexto = computed(() =>
    isEditing.value
        ? 'Atualizar Caminhoneiro'
        : 'Criar Caminhoneiro'
);

const form = useForm({

    id: caminhoneiro?.id ?? null,

    nome: caminhoneiro?.nome ?? '',

    cpf: caminhoneiro?.cpf ?? '',

    telefone: caminhoneiro?.telefone ?? '',

    placa_caminhao: caminhoneiro?.placa_caminhao ?? '',

    status: caminhoneiro?.status ?? 'Livre',
});

function enviar() {

    if (id_caminhoneiro !== null) {

        form.put(
            caminhoneiros.update(id_caminhoneiro).url,
            {
                onSuccess: () => {

                    toast.success(
                        'Caminhoneiro atualizado com sucesso'
                    );

                },

                onError: () => {

                    toast.error(
                        'Erro ao salvar'
                    );

                },
            }
        );

        return;
    }

    form.post(
        caminhoneiros.create().url,
        {
            onSuccess: () => {

                toast.success(
                    'Caminhoneiro criado com sucesso'
                );

            },

            onError: () => {

                toast.error(
                    'Erro ao salvar'
                );

            },
        }
    );
}
</script>

<template>

    <Head :title="pageTitle" />

    <AppLayout>

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <Heading
                :title="pageTitle"
                :description="pageDescription"
            />

            <div
                class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 p-4"
            >

                <Form @submit.prevent="enviar">

                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2"
                    >

                        <div class="grid gap-2">

                            <Label for="nome">
                                Nome
                            </Label>

                            <Input
                                id="nome"
                                v-model="form.nome"
                                placeholder="Nome do caminhoneiro"
                            />

                            <InputError
                                :message="form.errors.nome"
                            />

                        </div>

                        <div class="grid gap-2">

                            <Label for="cpf">
                                CPF
                            </Label>

                            <Input
                                id="cpf"
                                v-model="form.cpf"
                                placeholder="000.000.000-00"
                            />

                            <InputError
                                :message="form.errors.cpf"
                            />

                        </div>

                        <div class="grid gap-2">

                            <Label for="telefone">
                                Telefone
                            </Label>

                            <Input
                                id="telefone"
                                v-model="form.telefone"
                                placeholder="(43) 99999-9999"
                            />

                            <InputError
                                :message="form.errors.telefone"
                            />

                        </div>

                        <div class="grid gap-2">

                            <Label for="placa">
                                Placa do Caminhão
                            </Label>

                            <Input
                                id="placa"
                                v-model="form.placa_caminhao"
                                placeholder="ABC1D23"
                            />

                            <InputError
                                :message="form.errors.placa_caminhao"
                            />

                        </div>

                        <div class="grid gap-2">

                            <Label for="status">
                                Status
                            </Label>

                            <select
                                id="status"
                                v-model="form.status"
                                class="border rounded-md px-3 py-2"
                            >

                                <option value="Livre">
                                    Livre
                                </option>

                                <option value="EmViagem">
                                    Em Viagem
                                </option>

                                <option value="Folga">
                                    Folga
                                </option>

                            </select>

                            <InputError
                                :message="form.errors.status"
                            />

                        </div>

                    </div>

                    <div class="mt-6">

                        <Button
                            type="submit"
                            class="bg-yellow-400 text-black hover:bg-yellow-500"
                        >

                            <Plus
                                v-if="!isEditing"
                                class="mr-2 h-4 w-4"
                            />

                            <Pencil
                                v-else
                                class="mr-2 h-4 w-4"
                            />

                            {{ botaoTexto }}

                        </Button>

                    </div>

                </Form>

            </div>

        </div>

    </AppLayout>

</template>