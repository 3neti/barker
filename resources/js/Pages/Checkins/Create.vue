<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CreateCheckinForm from '@/Pages/Checkins/Partials/CreateCheckinForm.vue';

const props = defineProps({
    campaign: Object,
    type: Object,
    availableProfiles: Object
});

</script>

<template>
    <AppLayout title="Checkin">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Top
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <CreateCheckinForm />
            </div>
        </div>
    </AppLayout>
</template>
