import { makeHttpRequest } from "@/helper/makeHttpRequest";
import { ref } from 'vue';
import toastNotification from "@/helper/toastNotification"

export type RegisterUserType = {
    email: string,
    password: string,
    confirmationPassword: string,
}

export type RegisterResponseType = {
    user: {
        email: string
    },
    message: string
}

export const isValidConfirmationPassword = (password: string, confirmation: string) => {
    return password === confirmation;
};

export const registerInput = ref<RegisterUserType>({} as RegisterUserType)

export function useRegisterUser() {
    const loading = ref(false);
    async function register() {
        try {
            loading.value = true;
            const data = await makeHttpRequest<RegisterUserType, RegisterResponseType>('register', 'POST', registerInput.value);
            loading.value = false;
            registerInput.value = {} as RegisterUserType;
            toastNotification.showSuccess(data.message);
            window.location.href = '/verify-email';
        } catch (error) {
            console.log(error);
            loading.value = false;
            for (const message of error as string[]) {
                toastNotification.showError(message);
            }
        }
    }
    return { register, loading }
}