<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    checkin: Object,
    type: String,
});

</script>

<template>
    <ActionSection>
        <template #title>
            {{ $page.props.checkin.campaign.name }}
        </template>

        <template #description>
            {{ type }}
        </template>

        <template #content>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Scan the QR Code
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                <p>
                    or click the image
                </p>
            </div>

            <div class="mt-5">
                <a :href="checkin.url">
                    <img :src="checkin.QRCodeURI">
                </a>
            </div>

            <div class="mt-5">
                <template v-if="checkin.QRCodeURI">
                    <SecondaryButton type="button" @click="router.get(route('checkins.index'))">
                        List
                    </SecondaryButton>
                </template>
                <template v-else>
                    <SecondaryButton type="button" @click="router.post(route('generate-url', {transactionId: checkin.uuid}))">
                        Retry
                    </SecondaryButton>
                </template>
<!--                <div class="flex flex-row space-x-2">-->
<!--            -->
<!--         -->
<!--                </div>-->
            </div>
        </template>
    </ActionSection>
</template>
