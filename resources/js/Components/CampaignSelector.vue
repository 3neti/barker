<template>
    <!-- Campaigns Dropdown -->
    <div class="ml-3 relative">
        <Dropdown align="right" width="48">
            <template #trigger>
                <span class="inline-flex rounded-md">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                        {{ $page.props.auth.user.current_campaign.name }}

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

    <VueFinalModal v-model="showNewSiteModal" classes="flex justify-center items-start pt-16 md:pt-24 mx-4" content-class="relative max-h-full rounded bg-white w-full max-w-2xl p-4 md:p-6" overlay-class="bg-gradient-to-r from-gray-800 to-gray-500 opacity-50" :esc-to-close="true">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">New site</h2>

        <form v-on:submit.prevent="createSite" class="overflow-hidden space-y-4">
            <InputLabel for="domain" value="Domain" class="sr-only" />
            <TextInput id="domain" type="text" class="block w-full h-9 text-sm" placeholder="e.g. https://codecourse.com" v-model="siteForm.domain" :class="{ 'border-red-500': siteForm.errors.domain }" />
            <InputError class="mt-2" :message="siteForm.errors.domain" />

            <PrimaryButton>
                Add
            </PrimaryButton>
        </form>
    </VueFinalModal>
</template>

<script setup>
import {Link, router, useForm} from '@inertiajs/vue3'
import { VueFinalModal} from "vue-final-modal"
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { ref } from 'vue'

defineProps({
    sites: Array
})

const showNewSiteModal = ref(false)

const siteForm = useForm({
    domain: null
})

const createSite = () => {
    siteForm.post('/sites', {
        preserveScroll: true,
        onSuccess: () => {
            siteForm.reset()
            showNewSiteModal.value = false
        }
    })
}

const switchToCampaign = (campaign) => {
    router.put(route('current-campaign.update'), {
        campaign_id: campaign.id,
    }, {
        preserveState: false,
    });
};
</script>
