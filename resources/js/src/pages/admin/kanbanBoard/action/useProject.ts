import { ref } from 'vue';
import { makeHttpRequest } from "@/helper/makeHttpRequest";
import toastNotification from "@/helper/toastNotification";
import utility from "@/helper/utility";

export function useProject() {
    const projectActionLoading = ref(false);

    async function updateProjectTitle(projectId: string, name: string) {
        try {
            const data = await makeHttpRequest<{ id: string, name: string }, { message: string }>(
                `project`,
                'PUT',
                {
                    id: projectId,
                    name: name
                }
            );
            toastNotification.showSuccess(data.message);
        } catch (error) {
            projectActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    return { projectActionLoading, updateProjectTitle }
}