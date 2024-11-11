import { makeHttpRequest } from "@/helper/makeHttpRequest";
import toastNotification from "@/helper/toastNotification";
import utility from "@/helper/utility";

export function useVerifyEmail() {
    async function verifyEmail(token: string) {
        try {
            console.log(token);
            const data = await makeHttpRequest<undefined, {message: string}>(`verify-email/${token}`, 'POST');
            toastNotification.showSuccess(data.message);
            window.location.href = '/login';
        } catch (error) {
            utility.showErrorMessage(error);
        }
    }
    return { verifyEmail }
}