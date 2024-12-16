import { ref } from 'vue';
import { makeHttpRequest } from "@/helper/makeHttpRequest";
import toastNotification from "@/helper/toastNotification";
import utility from "@/helper/utility";

export type CreateListInput = {
    name: string,
    projectId: string
}

export type ListType = {
    id: string,
    name: string,
    projectId: string
    order: number
}

export function useList() {
    const listActionLoading = ref(false);

    async function createList(createListInput: CreateListInput, callback?: (result: ListType) => void) {
        try {
            listActionLoading.value = true;
            const data = await makeHttpRequest<CreateListInput, ListType>('list', 'POST', createListInput);
            listActionLoading.value = false;
            
            if (callback)
                callback(data);
        } catch (error) {
            listActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    async function deleteList(listId: string, projectId: string) {
        try {
            const data = await makeHttpRequest<{ projectId: string}, { message: string }>(
                `list/${listId}`,
                'DELETE',
                {
                    projectId: projectId
                }
            );
            toastNotification.showSuccess(data.message);
        } catch (error) {
            listActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    async function reorderList(listId: string, projectId: string, order: number) {
        try {
            const data = await makeHttpRequest<{ projectId: string, order: number }, { message: string }>(
                `list/${listId}`,
                'PUT',
                {
                    projectId: projectId,
                    order: order
                }
            );
            toastNotification.showSuccess(data.message);
        } catch (error) {
            listActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    async function editList(listId: string, projectId: string, name: string) {
        try {
            const data = await makeHttpRequest<{ projectId: string, name: string }, { message: string }>(
                `list/${listId}`,
                'PUT',
                {
                    projectId: projectId,
                    name: name
                }
            );
            // toastNotification.showSuccess(data.message);
        } catch (error) {
            listActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    return { listActionLoading, createList, deleteList, reorderList, editList }
}