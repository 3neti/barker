<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import {Head as Head, router, usePage} from '@inertiajs/vue3';
import QRCode from '@/Pages/Checkins/Partials/QRCode.vue'
import Profile from '@/Pages/Checkins/Partials/Profile.vue'

defineProps({
    checkin: Object,
    dataRetrieved: Boolean,
    fieldsExtracted: Array,
    idImageUrl: String,
    selfieImageUrl: String,
});

const page = usePage()

Echo.private(`checkin.${page.props.checkin.uuid}`)
    .listen(".url.generated", (e) => {
        router.get(route('checkins.show', {checkin: e.transactionId}))
    })
    .listen(".result.processed", (e) => {
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
                <template v-if="dataRetrieved">
                    <Profile :checkin="checkin" :fieldsExtracted="fieldsExtracted" :idImageUrl="idImageUrl" :selfieImageUrl="selfieImageUrl"/>
                </template>
                <template v-else>
                    <QRCode :checkin="checkin" />
                </template>
                <SectionBorder />
            </div>
        </div>
    </AppLayout>
</template>
