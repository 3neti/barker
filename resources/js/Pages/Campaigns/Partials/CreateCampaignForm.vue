<script setup>
import {useForm, usePage} from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import Checkbox from '@/Components/Checkbox.vue';

const channels = ref([]);

const form = useForm({
    name: '',
    type: null,
    mobile: null,
    email: null,
    url: null,
    missives: {otp: false, instruction: "", rider: ""},
});

const createCampaign = () => {
    form.post(route('campaigns.store'), {
        errorBag: 'createCampaign',
        preserveScroll: true,
    });
};

const availableTypes = computed(
    () => usePage().props.availableTypes,
);

const availableInstructions = computed(
    () => usePage().props.availableMissives['instructions'],
);

const availableRiders = computed(
    () => usePage().props.availableMissives['riders'],
);

</script>

<template>
    <FormSection @submitted="createCampaign">
        <template #title>
            Campaign Details
        </template>

        <template #description>
            Create a new campaign...
        </template>

        <template #form>
            <!-- Campaign Owner -->
            <div class="col-span-6">
                <InputLabel value="Owner" />

                <div class="flex items-center mt-2">
                    <img class="object-cover w-12 h-12 rounded-full" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">

                    <div class="ml-4 leading-tight">
                        <div class="text-gray-900 dark:text-white">{{ $page.props.auth.user.name }}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $page.props.auth.user.current_team.name }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="col-span-6 sm:col-span-4">
                <!-- Campaign Name -->
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                />
                <InputError :message="form.errors.name" class="mt-2" />

                <!-- Campaign Type -->
                <InputLabel for="type" value="Type" />
                <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                    <button
                        v-for="(type, i) in availableTypes"
                        :key="type.key"
                        type="button"
                        class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        :class="{'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableTypes).length - 1}"
                        @click="form.type = type.key; channels.value = type.channels"
                    >
                        <div :class="{'opacity-50': form.type && form.type != type.key}">
                            <!-- Type Name -->
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400" :class="{'font-semibold': form.type == type.key}">
                                    {{ type.name }}
                                </div>

                                <svg v-if="form.type == type.key" class="ml-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <!-- Type Description -->
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-left">
                                {{ type.description }}
                            </div>

                            <!-- Type Channels -->
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-left">
                                {{ type.channels }}
                            </div>
                        </div>
                    </button>
                </div>
                <InputError :message="form.errors.type" class="mt-2" />

                <SectionBorder />

                <!-- Mobile Channel -->
                <template v-if="channels.value?.includes('mobile')">
                    <InputLabel for="mobile" value="SMS Channel" />
                    <TextInput
                        id="mobile"
                        v-model="form.mobile"
                        type="text"
                        class="block w-full mt-1"
                        autofocus
                        placeholder="09173011987"
                    />
                    <InputError :message="form.errors.mobile" class="mt-2" />
                </template>


                <!-- Email Channel -->
                <template v-if="channels.value?.includes('email')">
                    <InputLabel for="email" value="Email Channel" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="text"
                        class="block w-full mt-1"
                        autofocus
                        placeholder="john.doe@email.com"
                    />
                    <InputError :message="form.errors.email" class="mt-2" />
                </template>

                <!-- Webhook Channel -->
                <template v-if="channels.value?.includes('webhook')">
                    <InputLabel for="url" value="Webhook Channel" />
                    <TextInput
                        id="webhook"
                        v-model="form.url"
                        type="text"
                        class="block w-full mt-1"
                        autofocus
                        placeholder="https://webhook.acme.com/result?secret=1234ABCD5678EFGH"
                    />
                    <InputError :message="form.errors.url" class="mt-2" />
                </template>

                <SectionBorder />

                <!-- Available Instructions -->
                <InputLabel for="instructions" value="Instruction" />
                <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                    <button
                        v-for="(missive, i) in availableInstructions"
                        :key="missive.key"
                        type="button"
                        class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        :class="{'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableInstructions).length - 1}"
                        @click="form.missives.instruction = missive.text"
                    >
                        <div :class="{'opacity-50': form.missives.instruction && form.missives.instruction != missive.text}">
                            <!-- Instruction Key -->
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400" :class="{'font-semibold': form.missives.instruction == missive.text}">
                                    {{ missive.key }}
                                </div>

                                <svg v-if="form.missives.instruction == missive.text" class="ml-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <!-- Instruction Text -->
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-left">
                                {{ missive.text }}
                            </div>
                        </div>
                    </button>
                </div>
                <TextInput
                    id="instruction"
                    v-model="form.missives.instruction"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                    placeholder="Send instruction here."
                />

                <!-- Available Riders -->
                <InputLabel for="riders" value="Rider" />
                <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                    <button
                        v-for="(missive, i) in availableRiders"
                        :key="missive.key"
                        type="button"
                        class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        :class="{'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableRiders).length - 1}"
                        @click="form.missives.rider = missive.text"
                    >
                        <div :class="{'opacity-50': form.missives.rider && form.missives.rider != missive.text}">
                            <!-- Instruction Key -->
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400" :class="{'font-semibold': form.missives.rider == missive.text}">
                                    {{ missive.key }}
                                </div>

                                <svg v-if="form.missives.rider == missive.text" class="ml-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <!-- Instruction Text -->
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-left">
                                {{ missive.text }}
                            </div>
                        </div>
                    </button>
                </div>
                <TextInput
                    id="instructions"
                    v-model="form.missives.rider"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                    placeholder="Send rider here."
                />

                <SectionBorder />

                <!-- OTP -->
                <InputLabel for="otp" value="Send OTP" />
                <Checkbox id="otp" v-model="form.missives.otp" :checked="form.missives.otp"/>
            </div>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </PrimaryButton>
        </template>
    </FormSection>
</template>
