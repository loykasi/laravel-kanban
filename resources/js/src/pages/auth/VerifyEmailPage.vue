<script setup>
    import { useRoute } from 'vue-router'
    import { useVerifyEmail } from './action/verifyEmail';

    const { verifyEmail } = useVerifyEmail();

    const route = useRoute();
    verifyEmail(route.params.token);
</script>
<template>
    Wait for verification
</template>