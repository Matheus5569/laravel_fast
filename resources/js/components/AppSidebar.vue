<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Filter, Folder, Gift, LayoutDashboard, LayoutGrid, ShoppingBag, User, Users } from 'lucide-vue-next';
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
//import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
import vendedores from '@/routes/vendedores';
import clientes from '@/routes/clientes';
import produto from '@/routes/produto';
import vendas from '@/routes/vendas';
import { computed } from 'vue';
import { dashboard } from '@/routes';
import rastreamento from '@/routes/rastreamento/index.js';
const page = usePage();

const roleUser = computed(() => (page.props as any).auth?.roleName ?? null);
const mainNavItems = [
    {
        title: 'Inicio',
        href: dashboard(),
        icon: LayoutDashboard,
    
    },
    {
        title: 'Vendedores',    
        href: vendedores.listar(),
        icon: User,
        permission: ['admin'],
    },
    {
        title: 'Clientes',
        href: clientes.listar(),
        icon: Users,
        permission: ['admin'],

    },
    {
        title: 'Produto',
        href: produto.produtos(),
        icon: Gift,
        permission: ['admin'],

    },
    {
        title: 'Venda',
        href: vendas.listar(),
        icon: ShoppingBag,

    },
    {
        title: 'Rastreamento',
        href: rastreamento.listar(),
        icon: ShoppingBag,

    },
];
function hasAccess(item: any) {
    if (!item.permission) return true;
    if (!roleUser.value) return false;

    if (item.permission.includes(roleUser.value)) {
        return true;
    }
    return false;
}
const filtereNavItens = computed(() => mainNavItems.filter(hasAccess));

//cê não funcionasse na tela agente coloca o npm run build
const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];
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
