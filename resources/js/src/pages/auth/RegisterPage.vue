<script setup>
    import { registerInput, useRegisterUser } from "./action/register";
    import { useVuelidate } from '@vuelidate/core';
    import { required, email } from '@vuelidate/validators';

    const rule = {
        email: { required, email },
        password: { required }
    }

    const v$ = useVuelidate(rule, registerInput);
    const { register, loading } = useRegisterUser();

    async function submitRegister() {
        const result = await v$.value.$validate();

        if (!result) return;

        await register();
        v$.value.$reset();
    }
</script>
<template>
    <div class="row">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2 align="center">Register</h2>
                        <form @submit.prevent="submitRegister">
                            <div class="form-group">
                                <Error :errors="v$.email.$errors" label="Email">
                                    <BaseInput v-model="registerInput.email" />
                                </Error>
                            </div>
                            <div class="form-group">
                                <Error :errors="v$.password.$errors" label="Password">
                                    <BaseInput v-model="registerInput.password" type="password"/>
                                </Error>
                            </div>
                            <div class="form-group">
                                <RouterLink to="/login">Login into your account</RouterLink>
                            </div>
                            <div class="form-group">
                                <BaseBtn label="Register" :loading="loading"></BaseBtn>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>