<script setup>
    import { useRoute } from 'vue-router'
    import { useVerifyEmail } from './action/verifyEmail';

    const { verifyEmail } = useVerifyEmail();

    const route = useRoute();
    verifyEmail(route.params.token);
</script>
<template>
    <div class="bg-gray-200 flex items-center justify-center h-screen">
        <div class="bg-white p-8 shadow-md rounded-md">
            Wait for verification
        </div>
    </div>
</template>