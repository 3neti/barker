<script setup>
import relativeTime from 'dayjs/plugin/relativeTime';
// import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import dayjs from 'dayjs';

// const formatter = new Intl.NumberFormat('en-PH', {
//     style: 'currency',
//     currency: 'PHP',
// });
//
// const page = usePage()

// Echo.private(`wallet.holder.${page.props.auth.user.id}`)
//     .listen(".balance.updated", (e) => {
//         router.get('/dashboard')
//     })

dayjs.extend(relativeTime);
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
