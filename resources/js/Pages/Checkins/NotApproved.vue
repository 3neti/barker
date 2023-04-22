<script setup>
import {Head as Head, router, usePage} from '@inertiajs/vue3';

defineProps({
    transactionId: String,
    status: String,
});

const page = usePage()

</script>

<template>
    <div>
        {{ status }}: {{ transactionId }}
    </div>
</template>
