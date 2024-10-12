<script setup>
    import { loginInput, useLoginUser } from "./action/login";
    import { useVuelidate } from '@vuelidate/core';
    import { required, email } from '@vuelidate/validators';

    const rule = {
        email: { required, email },
        password: { required }
    }

    const v$ = useVuelidate(rule, loginInput);
    const { login, loading } = useLoginUser();

    async function submitLogin() {
        const result = await v$.value.$validate();

        if (!result) return;

        await login();
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
                        <h2 align="center">Login</h2>
                        <form @submit.prevent="submitLogin">
                            <div class="form-group">
                                <Error :errors="v$.email.$errors" label="Email">
                                    <BaseInput v-model="loginInput.email" />
                                </Error>
                            </div>
                            <div class="form-group">
                                <Error :errors="v$.password.$errors" label="Password">
                                    <BaseInput v-model="loginInput.password" type="password"/>
                                </Error>
                            </div>
                            <div class="form-group">
                                <RouterLink to="/register">Create account</RouterLink>
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