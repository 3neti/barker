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
    handle: null,
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
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Checkin
            </PrimaryButton>
        </template>
    </FormSection>
</template>
