<script setup>
import {router, useForm} from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const switchToCampaign = (campaign) => {
    router.put(route('current-campaign.update'), {campaign_id: campaign.id,}, {preserveState: false,});
};
</script>
<template>
    <!-- Campaigns Dropdown -->
    <div class="ml-3 relative">
        <Dropdown align="left" width="48">
            <template #trigger>
                <span class="inline-flex rounded-md">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                        {{ $page.props.auth.user.current_campaign?.name || `Add Campaigns Here...` }}

                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                        </svg>
                    </button>
                </span>
            </template>
            <template #content>
                <!-- Campaign Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    Switch Campaigns
                </div>
                <template v-for="campaign in $page.props.auth.user.campaigns" :key="campaign.id">
                    <form @submit.prevent="switchToCampaign(campaign)">
                        <DropdownLink as="button">
                            <div class="flex items-center">
                                <svg v-if="campaign.id == $page.props.auth.user.current_campaign_id" class="mr-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>{{ campaign.name }}</div>
                            </div>
                        </DropdownLink>
                    </form>
                </template>
                <div class="border-t border-gray-200 dark:border-gray-600" />

                <!-- Authentication -->
                <DropdownLink :href="route('campaigns.create')">
                    New Campaign
                </DropdownLink>
            </template>
        </Dropdown>
    </div>
</template>
