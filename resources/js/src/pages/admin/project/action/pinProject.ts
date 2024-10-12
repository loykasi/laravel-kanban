import { ref } from "vue"
import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import utility from "../../../../helper/utility";
import toastNotification from "../../../../helper/toastNotification";

export function usePinProject() {
    const loading = ref(false);
    async function pinProject(projectId: number) {
        try {
            loading.value = true;
            const data = await makeHttpRequest<{ projectId: number }, { message: string }>(`project/pin`, 'POST', { projectId: projectId });
            loading.value = false;
            toastNotification.showSuccess(data.message);
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { pinProject, loading }
}