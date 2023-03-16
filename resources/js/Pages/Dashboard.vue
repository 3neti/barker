<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { router, usePage } from '@inertiajs/vue3';

const formatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

const page = usePage()

Echo.private(`wallet.holder.${page.props.auth.user.id}`)
    .listen(".balance.updated", (e) => {
        router.get('/dashboard')
    })

</script>

<template>
    <AppLayout title="Dashboard">
        {{ $page.props.auth.user.id }}
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ formatter.format($page.props.auth.user.balanceFloat) }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
