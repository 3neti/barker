<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import {Head as Head, router, usePage} from '@inertiajs/vue3';
import QRCode from '@/Pages/Checkins/Partials/QRCode.vue'

defineProps({
    checkin: Object,
});

const page = usePage()

Echo.private(`agent.${page.props.auth.user.id}`)
    .listen(".url.generated", (e) => {
        router.get(route('checkins.show', {checkin: e.transactionId}))
    })
</script>

<template>
    <AppLayout title="Checkin">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Checkin
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <QRCode :checkin="checkin" />

                <SectionBorder />
            </div>
        </div>
    </AppLayout>
</template>
