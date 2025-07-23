<template>
    <h1>This is Create sale page</h1>
    <p>{{ products }}</p>
</template>

<script setup>
    import { usePage, useForm, router } from '@inertiajs/vue3';
    import { createToaster } from "@meforma/vue-toaster";
    import { ref } from 'vue';

    const toaster = createToaster({
        position: "top-right",
    });

    let page = usePage();

</script>

<style scoped>

</style>
