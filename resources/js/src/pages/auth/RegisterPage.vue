<script setup>
    import { registerInput, useRegisterUser, isValidConfirmationPassword } from "./action/register";
    import { useVuelidate } from '@vuelidate/core';
    import { required, email, minLength, sameAs } from '@vuelidate/validators';
    import RegisterSuccessPage from './RegisterSuccessPage.vue'
    import { ref } from 'vue'

    const isSuccess = ref(false);

    const rule = {
        email: { required, email },
        password: { required, minLength: minLength(3) },
        confirmationPassword: { required, sameAsPassword: isValidConfirmationPassword(registerInput.value.password, registerInput.value.confirmationPassword) }
    }

    const v$ = useVuelidate(rule, registerInput);
    const { register, loading } = useRegisterUser();

    async function submitRegister() {
        console.log(registerInput.value)
        const result = await v$.value.$validate();

        if (!result) return;

        await register();
        v$.value.$reset();
    }
</script>
<template>
    <RegisterSuccessPage v-if="isSuccess"/>
    <div v-else class="bg-gray-200 flex items-center justify-center h-screen">
        <div class="bg-white p-8 shadow-md rounded-md w-2/6">
            <h2 class="text-2xl font-semibold mb-4 text-center">Register</h2>
            <form @submit.prevent="submitRegister">
                <div>
                    <Error :errors="v$.email.$errors" label="Email">
                        <BaseInput v-model="registerInput.email" />
                    </Error>
                </div>
                <div>
                    <Error :errors="v$.password.$errors" label="Password">
                        <BaseInput v-model="registerInput.password" type="password"/>
                    </Error>
                </div>
                <div>
                    <Error :errors="v$.confirmationPassword.$errors" label="Confirm Password">
                        <BaseInput v-model="registerInput.confirmationPassword" type="password"/>
                    </Error>
                </div>
                <div >
                    <BaseBtn label="Register" :loading="loading"></BaseBtn>
                </div>
                <div class="mt-3">
                    Already has an account?
                    <RouterLink to="/login" class="text-blue-700 hover:underline mt-3">Login here</RouterLink>
                </div>
            </form>
        </div>
    </div>
</template>