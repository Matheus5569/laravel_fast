<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import logoFast from '../../images/fast.png';
</script>

<template>
    <div class="flex items-center gap-2"></div>
    <img :src="logoFast" alt="Fast" class="h-6 w-auto" />
    <div class="ml-1 grid flex-1 text-left text-sm">
        
        <span class="mb-0.5 truncate leading-tight font-semibold">Projeto Fast</span>
    </div>
</template>
