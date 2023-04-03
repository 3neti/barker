<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    checkin: Object
});

</script>

<template>
    <ActionSection>
        <template #title>
            KwYC Check
        </template>

        <template #description>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Habitasse platea dictumst vestibulum rhoncus est pellentesque elit. Tempus urna et pharetra pharetra. Tortor id aliquet lectus proin.
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
                <div>
                    <SecondaryButton type="button" @click="router.get(route('checkins.index'))">
                        List
                    </SecondaryButton>
                </div>
            </div>
        </template>
    </ActionSection>
</template>
