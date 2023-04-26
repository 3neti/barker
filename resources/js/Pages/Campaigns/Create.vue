<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CreateCampaignForm from '@/Pages/Campaigns/Partials/CreateCampaignForm.vue';

const props = defineProps({
    availableTypes: Array,
    availableMissives: Object,
    availableProfiles: Object,
});

</script>

<template>
    <AppLayout title="Create Campaign">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Campaign
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <CreateCampaignForm />
            </div>
        </div>
    </AppLayout>
</template>
