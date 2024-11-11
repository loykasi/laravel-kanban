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
    <div class="bg-gray-200 flex items-center justify-center h-screen">
        <div class="bg-white p-8 shadow-md rounded-md">
            <h2 class="text-2xl font-semibold mb-4">Login</h2>
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
                    <RouterLink to="/register" class="block hover:text-blue-500 mt-3">Create account</RouterLink>
                </div>
                <div class="form-group">
                    <BaseBtn label="Register" :loading="loading"></BaseBtn>
                </div>
            </form>
        </div>
    </div>
</template>