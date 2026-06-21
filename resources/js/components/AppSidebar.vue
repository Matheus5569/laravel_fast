<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Filter,
    Folder,
    Gift,
    LayoutDashboard,
    LayoutGrid,
    ShoppingBag,
    User,
    Users,
    Truck,
    Plus,
    Grid
} from 'lucide-vue-next';

import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';

import vendedores from '@/routes/vendedores';
import clientes from '@/routes/clientes';
import produto from '@/routes/produto';
import vendas from '@/routes/vendas';
import rastreamento from '@/routes/rastreamento/index.js';
import dashboardFunc from '@/routes/dashboardFunc/index.js';
import caminhoneiros from '@/routes/caminhoneiros/index.js';
import funcionarios from '@/routes/funcionarios/index.js';
import baias from '@/routes/baias/index.js';

import { computed } from 'vue';
import { dashboard } from '@/routes';

const page = usePage();

const roleUser = computed(() => (page.props as any).auth?.roleName ?? null);

const mainNavItems = [
    {
        title: 'Inicio',
        href: dashboard(),
        icon: LayoutDashboard,
        permission: ['admin', 'vendedor', 'funcionario','clientes'],
    },
    // ADMIN
    {
        title: 'Gerentes',
        href: vendedores.listar(),
        icon: User,
        permission: ['admin', 'vendedor'],
    },
    {
        title: 'Funcionários',
        href: funcionarios.listar(),
        icon: Users,
        permission: ['admin', 'vendedor'],
    },
    {
        title: 'Clientes',
        href: clientes.listar(),
        icon: Users,
        permission: ['admin', 'vendedor'],
    },
    {
        title: 'Produtos',
        href: produto.produtos(),
        icon: Gift,
        permission: ['admin', 'vendedor'],
    },
    {
        title: 'Baias',
        href: baias.listar(),
        icon: Grid,
        permission: ['admin','vendedor'],
    },
    {
        title: 'Caminhoneiros',
        href: caminhoneiros.listar(),
        icon: Users,
        permission: ['admin','vendedor'],
    },

    // GERENTE
    {
        title: 'Orçamentos',
        href: vendas.listar(),
        icon: ShoppingBag,
        permission: ['admin', 'vendedor', 'clientes'],
    },
    {
        title: 'Rastreamentos',
        href: rastreamento.listar(),
        icon: Truck,
        permission: ['admin', 'funcionario'],
    },
    // FUNCIONÁRIO
    {
        title: 'Produção',
        href: dashboardFunc.producao(),
        icon: LayoutGrid,
        permission: ['funcionario'],
    },
];

function hasAccess(item: any) {
    if (!item.permission) return true;
    if (!roleUser.value) return false;

    return item.permission.includes(roleUser.value);
}

const filtereNavItens = computed(() => mainNavItems.filter(hasAccess));

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link>
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="filtereNavItens" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>