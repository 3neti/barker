<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Header
            </h2>
            <h3 class="font-sans text-sm text-gray-800 dark:text-gray-200 leading-tight">sub header</h3>
        </template>
    </AppLayout>
</template>
