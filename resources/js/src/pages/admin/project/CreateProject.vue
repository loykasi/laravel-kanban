<script setup>
    import { useProjectFunction } from "./action/createProject";
    import { useVuelidate } from '@vuelidate/core';
    import { required } from '@vuelidate/validators';
    import { projectStore } from "../../../store/projectStore";

    const rule = {
        name: { required },
        startDate: { required },
        endDate: { required },
    }

    const v$ = useVuelidate(rule, projectStore.projectInput);
    const { createProject, loading } = useProjectFunction();

    async function submitProject() {
        const result = await v$.value.$validate();

        if (!result) return;

        await createProject();
        v$.value.$reset();
    }
</script>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>Create project</h3>
                <form @submit.prevent="submitProject">
                    <div class="form-group">
                        <Error :errors="v$.name.$errors" label="Name">
                            <BaseInput v-model="projectStore.projectInput.name" />
                        </Error>
                    </div>
                    <div class="form-group">
                        <Error :errors="v$.startDate.$errors" label="StartDate">
                            <BaseInput type="date" v-model="projectStore.projectInput.startDate" />
                        </Error>
                    </div>
                    <div class="form-group">
                        <Error :errors="v$.endDate.$errors" label="EndDate">
                            <BaseInput type="date" v-model="projectStore.projectInput.endDate" />
                        </Error>
                    </div>
                    <div class="form-group">
                        <BaseBtn :class="projectStore.edit ? 'btn btn-warning' : 'btn btn-primary'" :label="projectStore.edit ? 'Update' : 'Create'" :loading="loading"></BaseBtn>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>