<script setup lang="ts">
import { onMounted } from "vue";
import { UserType, useProfile } from "./action/useProfile"
import { useRoute } from 'vue-router';

const { accountData, getProfile } = useProfile();
const route = useRoute();

onMounted(async () => {
    let id = route.params.id as string;
    getProfile(id)
})

</script>
<template>
    <div class="h-full w-full">
        <div class="container mx-auto h-full px-3">
            <div class="text-2xl font-semibold pt-2">
                Profile
            </div>
            <hr class="h-0.5 bg-black my-4">
            <div class="pt-2 flex items-center">
                <div
                    class="relative inline-flex items-center justify-center w-20 h-20 overflow-hidden bg-gray-200 rounded-full"
                >
                    <span v-if="accountData.email" class="font-medium text-4xl text-gray-600 pointer-events-none">{{ accountData?.email[0] }}</span>
                </div>
                <div class="ml-5">
                    {{ accountData?.email }}
                </div>
            </div>
        </div>
    </div>
</template>