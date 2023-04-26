<script setup>
import {useForm, usePage} from '@inertiajs/vue3';;
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionBorder from '@/Components/SectionBorder.vue'
import {computed} from "vue";

const form = useForm({
    mobile: null,
    handle: null,
    profile: {}
});

const createCheckin = () => {
    form.post(route('checkins.store'), {
        errorBag: 'createCheckin',
        preserveScroll: true,
    });
};

const availableProfiles = computed(
    () => usePage().props.availableProfiles,
);

</script>

<template>
    <FormSection @submitted="createCheckin">
        <template #title>
            {{ $page.props.campaign.name }}
        </template>

        <template #description>
            {{ $page.props.type.key }}
        </template>

        <template #form>
            <!-- Form -->
            <div class="col-span-6 sm:col-span-4">
                <!-- Mobile -->
                <InputLabel for="mobile" value="Mobile Number" />
                <TextInput
                    id="mobile"
                    v-model="form.mobile"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                    placeholder="e.g. (0917) 123-4567"
                />
                <InputError :message="form.errors.mobile" class="mt-2" />

                <!-- Handle -->
                <InputLabel for="handle" value="Handle" />
                <TextInput
                    id="handle"
                    v-model="form.handle"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                    placeholder="Name|Description of the contact"
                />
                <InputError :message="form.errors.handle" class="mt-2" />
            </div>

            <template v-for="profile in availableProfiles">
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel :value="profile.key" />
                    <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                        <ul class="items-center w-full rounded-lg sm:flex">
                            <template v-for="option in profile.options" :key="option">
                                <li @click="form.profile[profile.key] = option" class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                    <div class="flex items-center pl-3">
                                        <input :id="profile.key.concat(option)" type="radio" :name="profile.key" value="male" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label :for="profile.key.concat(option)" class="w-full py-3 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ option }}</label>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </template>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Checkin
            </PrimaryButton>
        </template>
    </FormSection>
</template>
