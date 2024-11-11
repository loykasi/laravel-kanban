import { makeHttpRequest } from "@/helper/makeHttpRequest";
import { ref } from 'vue';
import toastNotification from "@/helper/toastNotification";
import utility from "@/helper/utility";

export type LoginUserType = {
    email: string,
    password: string
}

export type LoginResponseType = {
    user: {
        id: number,
        email: string
    },
    message: string,
    isLoggedIn: boolean,
    token: string
}

export const loginInput = ref<LoginUserType>({} as LoginUserType)

export function useLoginUser() {
    const loading = ref(false);
    async function login() {
        try {
            loading.value = true;
            const data = await makeHttpRequest<LoginUserType, LoginResponseType>('login', 'POST', loginInput.value);
            loading.value = false;
            loginInput.value = {} as LoginUserType;
            toastNotification.showSuccess(data.message);

            if (data.isLoggedIn) {
                localStorage.setItem('userData', JSON.stringify(data));
                window.location.href = '/project';
            }

        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { login, loading }
}