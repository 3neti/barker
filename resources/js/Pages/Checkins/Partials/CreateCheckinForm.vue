<script setup>
import {useForm, usePage} from '@inertiajs/vue3';;
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {computed} from "vue";

const form = useForm({
    mobile: null,
    email: null,
});

const createCheckin = () => {
    form.post(route('checkins.store'), {
        errorBag: 'createCheckin',
        preserveScroll: true,
    });
};

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
                <InputLabel for="mobile" value="SMS Channel" />
                <TextInput
                    id="sms"
                    v-model="form.mobile"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                    placeholder="09173011987"
                />
                <InputError :message="form.errors.mobile" class="mt-2" />


                <!-- Email -->
                <InputLabel for="email" value="Email Channel" />
                <TextInput
                    id="sms"
                    v-model="form.email"
                    type="text"
                    class="block w-full mt-1"
                    autofocus
                    placeholder="john.doe@email.com"
                />
                <InputError :message="form.errors.email" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Checkin
            </PrimaryButton>
        </template>
    </FormSection>
</template>
