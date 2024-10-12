<script setup>
    import { useMemberFunction } from "./action/createMember";
    import { useVuelidate } from '@vuelidate/core';
    import { required, email } from '@vuelidate/validators';
    import { memberStore } from "../../../store/memberStore";

    const rule = {
        email: { required, email },
        name: { required }
    }

    const v$ = useVuelidate(rule, memberStore.memberInput);
    const { createMember, loading } = useMemberFunction();

    async function submitMember() {
        const result = await v$.value.$validate();

        if (!result) return;

        await createMember();
        v$.value.$reset();
    }
</script>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>Create member</h3>
                <form @submit.prevent="submitMember">
                    <div class="form-group">
                        <Error :errors="v$.name.$errors" label="Name">
                            <BaseInput v-model="memberStore.memberInput.name" />
                        </Error>
                    </div>
                    <div class="form-group">
                        <Error :errors="v$.email.$errors" label="Name">
                            <BaseInput v-model="memberStore.memberInput.email" />
                        </Error>
                    </div>
                    <div class="form-group">
                        <BaseBtn :class="memberStore.edit ? 'btn btn-warning' : 'btn btn-primary'" :label="memberStore.edit ? 'Update' : 'Create'" :loading="loading"></BaseBtn>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>