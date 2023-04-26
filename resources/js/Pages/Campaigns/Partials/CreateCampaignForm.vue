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

const chosenChannels = ref([]);
const showRiders = ref(true);
const showOTP = ref(true);

const form = useForm({
    name: '',
    type: null,
    email: usePage().props.auth.user.email,
    mobile: usePage().props.auth.user.mobile,
    url: null,
    missives: {otp: false, instruction: "", rider: ""},
    profiles: [],
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

const availableProfiles = computed(
    () => usePage().props.availableProfiles,
);

const chooseInstruction = (missive) => {
    let teamName = usePage().props.auth.user.current_team.alias
        ? usePage().props.auth.user.current_team.alias
        : usePage().props.auth.user.current_team.name;
    form.name = teamName + ' - ' + missive.key;
    form.missives.instruction = missive.text;
    form.type = missive.type.key;
    channels.value = missive.type.channels;
}

const checkProfile = (key) => {

}

</script>

<template>
    <FormSection @submitted="createCampaign">
        <template #title>
            Campaign Details
        </template>

        <template #description>
            Create a new campaign...
            <p/>
            {{ form.profiles }}
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
                    placeholder="input campaign name here..."
                />
                <InputError :message="form.errors.name" class="mt-2" />



            </div>
            <!-- Template Instructions -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="instructions" value="Instruction Templates" />
                <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                    <button
                        v-for="(missive, , index) in availableInstructions"
                        :key="missive.key"
                        type="button"
                        class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        :class="{'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': index > 0, 'rounded-b-none': index !== Object.keys(availableInstructions).length - 1}"
                        @click="chooseInstruction(missive)"
                    >
                        <div :class="{'opacity-50': form.missives.instruction && form.missives.instruction !== missive.text}">
                            <!-- Instruction Key -->
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400" :class="{'font-semibold': form.missives.instruction === missive.text}">
                                    {{ missive.key }}
                                </div>

                                <svg v-if="form.missives.instruction === missive.text" class="ml-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <!-- Instruction Text -->
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-left">
                                {{ missive.description }}
                            </div>
                        </div>
                    </button>
                </div>
                <textarea
                    id="instruction"
                    type="text"
                    v-model="form.missives.instruction"
                    rows="3"
                    class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    autofocus
                    placeholder="input instructions here..."
                />
            </div>
            <!-- Profiles -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel value="Profiles" />
                <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                    <ul>
                        <template v-for="(profile, key, index) in availableProfiles">
                            <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                <div class="flex items-center pl-3">
                                    <input v-model="form.profiles" :id="profile.key.concat(index)" type="checkbox" :value="key" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label :for="profile.key.concat(index)" class="w-full py-3 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ key }}</label>
                                </div>
                                <div class="ml-2 text-sm">
                                    <label :for="profile.key.concat(index)" class="font-medium text-gray-900 dark:text-gray-300">{{ profile.description }}</label>
                                    <p class="text-xs font-normal text-gray-500 dark:text-gray-300">options: {{ profile.options.map(d => `"${d}"`).join([separator = ' | ']) }}</p>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <!-- Missives -->
            <!-- Riders -->
            <div class="col-span-6 sm:col-span-4" v-show="showRiders">
                <InputLabel for="riders" value="SMS Rider" />
                <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                    <button
                        v-for="(missive, , index) in availableRiders"
                        :key="missive.key"
                        type="button"
                        class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        :class="{'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': index > 0, 'rounded-b-none': index != Object.keys(availableRiders).length - 1}"
                        @click="form.missives.rider = missive.text"
                    >

                        <div :class="{'opacity-50': form.missives.rider && form.missives.rider != missive.text}">
                            <!-- Rider Key -->
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400" :class="{'font-semibold': form.missives.rider == missive.text}">
                                    {{ missive.key }}
                                </div>

                                <svg v-if="form.missives.rider == missive.text" class="ml-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <!-- Rider Description -->
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-left">
                                {{ missive.description }}
                            </div>

                            <!-- Rider Text -->
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-left">
                                <i>
                                    "{{ missive.text }}"
                                </i>
                            </div>
                        </div>
                    </button>
                </div>
                <textarea
                    id="instructions"
                    type="text"
                    v-model="form.missives.rider"
                    rows="3"
                    class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    autofocus
                    placeholder="input sms rider here..."
                />
            </div>
            <!-- OTP -->
            <div class="col-span-6 sm:col-span-4" v-show="showOTP">
                <div class="flex items-start mb-6">
                    <div class="flex items-center h-5">
                        <Checkbox id="otp" v-model="form.missives.otp" :checked="form.missives.otp" class="w-4 h-4"/>
                    </div>
                    <InputLabel for="otp" value="Send OTP" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"/>
                </div>
            </div>
            <!-- Data Usage | Campaign Type -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="type" value="Data Usage" />
                <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                    <button
                        v-for="(type, i) in availableTypes"
                        :key="type.key"
                        type="button"
                        class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        :class="{'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableTypes).length - 1}"
                        @click="form.type = type.key; channels = type.channels; chosenChannels = type.channels"
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
                                transmit via: {{ type.channels.join([separator = ', ']) }}
                            </div>
                        </div>
                    </button>
                </div>
                <InputError :message="form.errors.type" class="mt-2" />
            </div>
            <!-- Channels -->
            <div class="col-span-6 sm:col-span-4">
                <!-- Email -->
                <div v-show="channels?.includes('email')">
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm block font-medium text-sm text-gray-700 dark:text-gray-300">........email</span>
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="text"
                            class="block w-full mt-1"
                            autofocus
                            placeholder="input email address here..."
                            :disabled="!channels?.includes('email')"
                        />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>
                </div>
                <!-- Mobile -->
                <div v-show="channels?.includes('mobile')">
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm block font-medium text-sm text-gray-700 dark:text-gray-300">.....mobile</span>
                        <TextInput
                            id="mobile"
                            v-model="form.mobile"
                            type="text"
                            class="block w-full mt-1"
                            autofocus
                            placeholder="input mobile number here..."
                            :disabled="!channels?.includes('mobile')"
                        />
                        <InputError :message="form.errors.mobile" class="mt-2" />
                    </div>
                </div>
                <!-- Webhook -->
                <div v-show="channels?.includes('webhook')">
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm block font-medium text-sm text-gray-700 dark:text-gray-300">webhook</span>
                        <TextInput
                            id="webhook"
                            v-model="form.url"
                            type="text"
                            class="block w-full mt-1"
                            autofocus
                            placeholder="input webhook url here..."
                            :disabled="!channels?.includes('webhook')"
                        />
                        <InputError :message="form.errors.url" class="mt-2" />
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </PrimaryButton>
        </template>
    </FormSection>
</template>
