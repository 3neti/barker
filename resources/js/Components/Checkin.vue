<script setup>
import CampaignSelector from '@/Components/CampaignSelector.vue'
import { Link as Link } from '@inertiajs/vue3'
defineProps({
    checkins: Array,
});
</script>

<template>
    <div>
        <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

            <div class="flex items-center justify-between">
                <h2 class="ml-3 text-xl font-semibold text-gray-900 dark:text-white">
                    <Link :href="route('checkins.create')" as="button">Checkin...</Link>
                </h2>

                <div>
                    <CampaignSelector/>
                </div>
            </div>
        </div>

        <!-- the list of checkins -->

    </div>
</template>
