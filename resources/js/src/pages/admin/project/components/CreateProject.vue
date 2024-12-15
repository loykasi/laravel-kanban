<script setup lang="ts">
    import { useProjectFunction } from "../action/createProject";
    import { useVuelidate } from '@vuelidate/core';
    import { required } from '@vuelidate/validators';
    import { projectStore } from "@/store/projectStore";
    import { onMounted } from "vue";
    import { getUserData } from "@/helper/getUserData";

    defineProps<{
        showModal: boolean
    }>();

    const emit = defineEmits<{
        (e: "closeModal"): void
    }>()

    const rule = {
        name: { required },
        userId: { required }
    }

    const v$ = useVuelidate(rule, projectStore.projectInput);
    const { createProject, loading } = useProjectFunction();

    const userData = getUserData()

    async function submitProject() {
        projectStore.projectInput.userId = userData ? userData.user.id : "";
        console.log(projectStore.projectInput)
        // const result = await v$.value.$validate();

        // if (!result) return;

        await createProject();
        v$.value.$reset();
    }
</script>
<template>
    <div v-if="showModal" class="fixed h-dvh top-0 right-0 left-0 overflow-hidden z-50">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
        <div class="flex justify-center items-center w-full h-full">
            <div class="relative bg-white w-full max-w-sm rounded-lg shadow-lg">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Create project
                    </h3>
                    <button
                        @click="emit('closeModal')"
                        type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <form class="space-y-4" @submit.prevent="submitProject">
                        <!-- <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Project title</label>
                            <input type="name" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                        </div> -->
                        <div>
                            <Error :errors="v$.name.$errors" label="Name">
                                <BaseInput v-model="projectStore.projectInput.name" />
                            </Error>
                        </div>
                        <!-- <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Create</button> -->
                        <BaseBtn :class="projectStore.edit ? 'btn btn-warning' : 'btn btn-primary'" :label="projectStore.edit ? 'Update' : 'Create'" :loading="loading"></BaseBtn>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>