<script setup>
import relativeTime from 'dayjs/plugin/relativeTime';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import dayjs from 'dayjs';

const formatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

const page = usePage()

Echo.private(`wallet.holder.${page.props.auth.user.id}`)
    .listen(".balance.updated", (e) => {
        router.get('/dashboard')
    })

dayjs.extend(relativeTime);
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ formatter.format($page.props.auth.user.balanceFloat) }}
            </h2>
            <h3 class="font-sans text-sm text-gray-800 dark:text-gray-200 leading-tight">{{ dayjs($page.props.auth.user.balanceUpdatedAt).fromNow() }}</h3>
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
