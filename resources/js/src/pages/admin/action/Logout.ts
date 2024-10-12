import { makeHttpRequest } from "../../../helper/makeHttpRequest";
import { ref } from 'vue';
import toastNotification from "../../../helper/toastNotification";
import utility from "../../../helper/utility";

export function useLogoutUser() {
    const loading = ref(false);
    async function logout(userId: number) {
        try {
            loading.value = true;
            const data = await makeHttpRequest<{userId: number}, {message: string}>('logout', 'POST', {userId: userId});
            loading.value = false;
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { logout, loading }
}