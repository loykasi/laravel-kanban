import { makeHttpRequest } from "@/helper/makeHttpRequest";
import { ref } from 'vue';
import toastNotification from "@/helper/toastNotification";
import utility from "@/helper/utility";
import { taskStore } from "@/store/kanbanStore";

export type CreateTaskInput = {
    name: string,
    memberIds: Array<number>,
    projectId: number
}

export function useCreateTask() {
    const loading = ref(false);
    async function createTask() {
        try {
            console.log("try create task");
            loading.value = true;
            const data = await makeHttpRequest<CreateTaskInput, { message: string }>('task', 'POST', taskStore.taskInput);
            loading.value = false;
            // taskStore.taskInput = {} as CreateTaskInput;
            toastNotification.showSuccess(data.message);
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { createTask, loading }
}