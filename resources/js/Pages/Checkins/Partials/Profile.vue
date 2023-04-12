<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SectionBorder from '@/Components/SectionBorder.vue';

const props = defineProps({
    checkin: Object,
    fieldsExtracted: Array,
    idImageUrl: String,
    selfieImageUrl: String
});

</script>

<template>
    <ActionSection>
        <template #title>
            {{ usePage().props.checkin.campaign.name }}
        </template>

        <template #description>
            <p>
                Agent: {{ usePage().props.checkin.agent.name }}
            </p>
            <p>
                Date/Time: {{ usePage().props.checkin.data_retrieved_at }}
            </p>
            <!-- ID Image -->
            <div class="mt-5">
                <img :src="usePage().props.idImageUrl">
            </div>
            <!-- Selfie Image -->
            <div class="mt-5">
                <img :src="usePage().props.selfieImageUrl">
            </div>
        </template>

        <template #content>
            <template v-for="(value,label) in usePage().props.fieldsExtracted" >
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel :for="label" :value="label" />
                    <TextInput
                        :id="label"
                        :value="value"
                        type="text"
                        class="mt-1 block w-full"
                        readonly
                    />
                </div>
            </template>
            <SectionBorder />
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
