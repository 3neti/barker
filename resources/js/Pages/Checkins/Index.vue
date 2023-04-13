<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import {Head as Head, usePage, router} from "@inertiajs/vue3";
import CampaignSelector from '@/Components/CampaignSelector.vue'
import {computed, ref} from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

defineProps({
    checkins: Object,
    campaign: Object,
    type: Array,
    channels: Array
});

const formatter = new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'});
const balanceFloat = computed(
    () => formatter.format(usePage().props.auth.user.balanceFloat)
);
const canCheckin = computed(
    () => usePage().props.auth.user.campaigns?.length > 0
);

const newCheckin = () => {
    router.get(route('checkins.create'))
}

const showCheckin = (transactionId) => {
    router.get(route('checkins.show', { checkin: transactionId }))
}

const toTitleCase = (str) => {
    return str.toLowerCase().replace(/(^|\s|-|\')(\w)/g, function (match) {
        return match.toUpperCase();
    });
}

const showData = ref(false);

const toggleShowData = () => {
    showData.value = ! showData.value;
};

Echo.private(`wallet.holder.${usePage().props.auth.user.id}`)
    .listen(".balance.updated", (e) => {
        router.get('/dashboard')
    })
</script>

<template>
    <Head>
        <title>Checkin</title>
    </Head>
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <CampaignSelector/>
                </div>
                <div class="text-gray-800 dark:text-gray-200 leading-tight">
                    {{ balanceFloat }}
                </div>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <section class="container px-4 mx-auto">
                        <!-- Upper Part of Table -->
                        <div class="sm:flex sm:items-center sm:justify-between">
                            <!-- Table Label -->
                            <div>
                                <div class="flex items-center gap-x-3">
                                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">{{ toTitleCase(usePage().props.type.key) }} requests</h2>

                                    <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">{{ checkins.length }} records</span>
                                </div>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">{{ usePage().props.channels }}</p>
                            </div>

                            <div class="flex items-center mt-4 gap-x-3">
                                <!-- Transmit Data Button -->
                                <button class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_3098_154395)">
                                            <path d="M13.3333 13.3332L9.99997 9.9999M9.99997 9.9999L6.66663 13.3332M9.99997 9.9999V17.4999M16.9916 15.3249C17.8044 14.8818 18.4465 14.1806 18.8165 13.3321C19.1866 12.4835 19.2635 11.5359 19.0351 10.6388C18.8068 9.7417 18.2862 8.94616 17.5555 8.37778C16.8248 7.80939 15.9257 7.50052 15 7.4999H13.95C13.6977 6.52427 13.2276 5.61852 12.5749 4.85073C11.9222 4.08295 11.104 3.47311 10.1817 3.06708C9.25943 2.66104 8.25709 2.46937 7.25006 2.50647C6.24304 2.54358 5.25752 2.80849 4.36761 3.28129C3.47771 3.7541 2.70656 4.42249 2.11215 5.23622C1.51774 6.04996 1.11554 6.98785 0.935783 7.9794C0.756025 8.97095 0.803388 9.99035 1.07431 10.961C1.34523 11.9316 1.83267 12.8281 2.49997 13.5832" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_3098_154395">
                                                <rect width="20" height="20" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>

                                    <span>Transmit Data</span>
                                </button>
                                <!-- KwYC Check Button -->
                                <button @click="newCheckin" :disabled="! canCheckin" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>

                                    <span>KwYC Check</span>
                                </button>
                            </div>
                        </div>
                        <!-- Filter Buttons -->
                        <div class="mt-6 md:flex md:items-center md:justify-between">
                            <div class="inline-flex overflow-hidden bg-white border divide-x rounded-lg dark:bg-gray-900 rtl:flex-row-reverse dark:border-gray-700 dark:divide-gray-700">
                                <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 bg-gray-100 sm:text-sm dark:bg-gray-800 dark:text-gray-300">
                                    View all
                                </button>

                                <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">
                                    -Pending-
                                </button>

                                <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">
                                    -Failed-
                                </button>

                                <button class="px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">
                                    -Verified-
                                </button>
                            </div>
                            <div class="relative flex items-center mt-4 md:mt-0">
                                <span class="absolute">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-3 text-gray-400 dark:text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </span>
                                <input type="text" placeholder="Search" class="block w-full py-1.5 pr-5 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                            </div>
                        </div>

                        <div class="flex flex-col mt-6">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <!-- Headers -->
                                            <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    Contact
                                                </th>

                                                <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    Identification
                                                </th>
                                                <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                    <button @click="toggleShowData" class="flex items-center gap-x-3 focus:outline-none">
                                                        <span>Extracted Fields</span>

                                                        <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                            <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                            <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                                        </svg>

                                                    </button>
                                                </th>

                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">Face Check</th>

                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">ID Check</th>

                                                <th scope="col" class="relative py-3.5 px-4">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <!-- Body -->
                                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                            <!-- Start of row -->
                                            <tr v-for="checkin in usePage().props.checkins" :key="checkin.id">
                                                <!-- Contact Column -->
                                                <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                    <div>
                                                        <h2 class="font-medium text-gray-800 dark:text-white ">{{ checkin.person.name }}</h2>
                                                        <p class="text-sm font-normal text-gray-600 dark:text-gray-400">{{ dayjs(checkin.data_retrieved_at).fromNow() }}</p>
                                                    </div>
                                                </td>
                                                <!-- Column 2 -->
                                                <td class="px-8 py-4 text-sm font-medium whitespace-nowrap">
                                                    <div class="inline px-3 py-1 text-sm font-normal rounded-full text-blue-500 gap-x-2 bg-blue-100/60 dark:bg-gray-800">
                                                        {{ checkin.data.idType }}
                                                    </div>
                                                </td>
                                                <!-- Column 3 -->
                                                <td class="px-12 py-4 text-sm whitespace-nowrap">
                                                    <div v-for="(value, index) in checkin.data.fieldsExtracted" class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                        <template v-if="showData">
                                                            <p class="px-3 py-1 text-sm text-emerald-500 rounded-full dark:bg-gray-800 bg-emerald-100/60">{{ value }}</p>
                                                        </template>
                                                        <template v-else>
                                                            <p class="px-3 py-1 text-sm text-blue-500 rounded-full dark:bg-gray-800 bg-blue-100/60">{{ index }}</p>
                                                        </template>
                                                    </div>
                                                </td>
                                                <!-- Column 4 -->
                                                <td class="px-12 py-4 text-sm whitespace-nowrap">
                                                    <div v-for="(value, index) in checkin.data.selfieChecks" class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                        <template v-if="value == 'no'">
                                                            <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>{{ index }}</del></p>
                                                        </template>
                                                        <template v-else>
                                                            <p class="px-3 py-1 text-sm text-red-500 rounded-full dark:bg-gray-800 bg-red-100/60">{{ index }}</p>-->
                                                        </template>
<!--                                                        <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-gray-700 shrink-0" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">-->
<!--                                                        <p class="px-3 py-1 text-sm text-red-500 rounded-full dark:bg-gray-800 bg-red-100/60">RETAKE</p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-emerald-500 rounded-full dark:bg-gray-800 bg-emerald-100/60">live face</p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>blur</del></p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>eyes closed</del></p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>mask present</del></p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>multiple faces</del></p>-->
                                                    </div>
                                                </td>
                                                <!-- Column 5 -->
                                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                    <div v-for="(value, index) in checkin.data.idChecks" class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                        <template v-if="value == 'no'">
                                                            <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>{{ index }}</del></p>
                                                        </template>
                                                        <template v-else>
                                                            <p class="px-3 py-1 text-sm text-red-500 rounded-full dark:bg-gray-800 bg-red-100/60">{{ index }}</p>-->
                                                        </template>
<!--                                                        <p class="px-3 py-1 text-sm text-emerald-500 rounded-full dark:bg-gray-800 bg-emerald-100/60">{{ index }}:{{ value }}</p>-->
<!--                                                        <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-gray-700 shrink-0" src="https://prod-audit-portal-sgp.s3.ap-southeast-1.amazonaws.com/gkyc-ap-southeast-1/readId/2023-02-24/12dqkm/1677231216715-f9183f16-d26c-492c-84b8-d89a19025c31/cropped.jpeg?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIAZRKK5ZMRZ3IN3VWS%2F20230224%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20230224T121750Z&X-Amz-Expires=900&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEGwaCmFwLXNvdXRoLTEiRjBEAiAU9QkLu6LeGVALccMsO5LeSqgnGH1UWJpgvQSty8uyPwIgAklKiz0%2F8Sij2YvWNFTAMMorMl7ssS87AYZLwHyJeAcqvwUIFRABGgw2NTU2NzY1MjUzNDciDM3VbaaaWD8Xi%2FKTXiqcBePfcoo%2FmE33G5Y9Tyk1mqcJfEa1Ufgt9bi%2FKPGKqebTgdyMrG6bGZGn%2BcB2xyZkztmUJ%2ByzZeaBG9stEZKwuEZFyQxXw6kF2iSIkWZq3%2F7ZThb9FDhMP0DjYMEdimfQO45BSbsJAZ%2BTEV1lttF1qOOUrXSOs5W%2BCJhW06RgeiPh6xzCoDv236MAswmsEE%2BWzc6ca28Eeu9d71Mas0P%2FJti0pktsd%2BZbIG0PRkfrOGyaS3EXRbZV4Ju5JOAhyq%2FaMoPPPSi35XIOiRGCOyo7y2TX1y6Qra%2BAmVFlFGqNrXBbU6w0Iht%2F%2B5PfYJsrCxMCSCNZuqCwLuGU5J7EN1u5FRyYipNWo%2FujTLeeuX4r1xWyx2iIz1ZrKVMOT5%2B%2Fkmjl7Rgtzf1DluF4OMmwn942m%2BNS8niMahYODiA2%2FuC9b1DlIJ7bSk4XzavkkxtbhTNOXOs5q2dm%2Bl92ZFb9oNxJClV1bXh36ML8XKMbJH7CI62bnm4O5PPMVfKiHPrsGrbSmNylHAc2lgWuMaMAN1hsePBp%2Fv%2FFe8drrYs5Nskvn1RuDFEN76xW59kXKikz2%2FtFD5UkPZrBJ2F2qYCaD6FjjRpL4VNoObfMygtYBvqCdPH8JZSa3fAWlYp%2FngnGBAYbjdPV0Y6oYOqd5sHrf8z0qW9aFAYgkwWoEH%2B6ExkdW0IQ5QHr1w%2FB8D2gIcg2AuyFRBtkV5Dq0spy6R8nt%2FTKneq8RxONnEK6EuxkhbPqMz3yka2lN%2F3E2r3gkSWE33nobIRR0dZNVK8g1o2AdWKDR8KBr%2FOqANI0ITQV306bYZAvJwctyZcxxRfvcQuuPQi0KJs9cA3%2Bfw55zX5jX4cbz6u9c3FLK35BHR2Qh%2B%2B68BOCSbVXXZWfn9D5dbdFMNzM4p8GOrIBaFnGHF3wAAOAQ8K6ZRHvQ6%2FQysVnYZlQihROYF%2FWDWPOUrPbUGij%2FT1zb%2F1L42ifBOWTdcJYhSDxXX%2FKAtOiyl3%2B1zlYKVyjwe84g81wcBF8q7ancEX7XUtnVGqCRgdWlkwcq6wqX%2FY6GGLNtH3QfNOmpTmZ456Gf7Wa%2Ffu4enLNEGOgD2TzODD9J8AjS94o9xTvkP%2FCqZKgikDO3NvRxnULHfTeiF%2Fo7ukCFIgfr3twsA%3D%3D&X-Amz-Signature=dc26b51a73c64fe249d42e68e32420e84c4d7c46aa3918ae1aa49eea33154272&X-Amz-SignedHeaders=host" alt="">-->
<!--                                                        <p class="px-3 py-1 text-sm text-emerald-500 rounded-full dark:bg-gray-800 bg-emerald-100/60">PASS</p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-red-500 rounded-full dark:bg-gray-800 bg-red-100/60">blur</p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>glare</del></p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>black and white</del></p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>captured from screen</del></p>-->
<!--                                                        <p class="px-3 py-1 text-sm text-gray-500 rounded-full dark:bg-gray-800 bg-gray-100/60"><del>partial id</del></p>-->
                                                    </div>
                                                </td>
                                                <!-- Column 6 -->
                                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                    <button @click="showCheckin(checkin.uuid)" class="px-1 py-1 text-gray-500 transition-colors duration-200 rounded-lg dark:text-gray-300 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- End of row -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 sm:flex sm:items-center sm:justify-between ">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Page <span class="font-medium text-gray-700 dark:text-gray-100">1 of 10</span>
                            </div>

                            <div class="flex items-center mt-4 gap-x-4 sm:mt-0">
                                <a href="#" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                                    </svg>

                                    <span>
                    previous
                </span>
                                </a>

                                <a href="#" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                <span>
                    Next
                </span>

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
