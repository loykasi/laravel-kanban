import { ref } from 'vue';
import { makeHttpRequest } from "@/helper/makeHttpRequest";
import toastNotification from "@/helper/toastNotification";
import utility from "@/helper/utility";

export type CreateCardInput = {
    name: string,
    listId: string,
    projectId: string,
}

export type CardType = {
    id: string,
    name: string,
    listId: string
    order: number
}


export function useCard() {
    const cardActionLoading = ref(false);

    async function createCard(createInput: CreateCardInput, callback?: (result: CardType) => void) {
        try {
            cardActionLoading.value = true;
            const data = await makeHttpRequest<CreateCardInput, CardType>('card', 'POST', createInput);
            cardActionLoading.value = false;
            
            if (callback)
                callback(data);
        } catch (error) {
            cardActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    async function deleteCard(cardId: string, projectId: string) {
        try {
            const data = await makeHttpRequest<{ projectId: string }, { message: string }>(
                `card/${cardId}`,
                'DELETE',
                {
                    projectId: projectId
                }
            );
            toastNotification.showSuccess(data.message);
        } catch (error) {
            cardActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    async function reorderCard(cardId: string, fromListId: string, toListId: string, projectId: string, order: number) {
        try {
            const data = await makeHttpRequest<{ fromListId: string, toListId: string, projectId: string, order: number }, { message: string }>(
                `card/${cardId}`,
                'PUT',
                {
                    fromListId: fromListId,
                    toListId: toListId,
                    projectId: projectId,
                    order: order
                }
            );
            // toastNotification.showSuccess(data.message);
        } catch (error) {
            cardActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    async function editCard(cardId: string, listId: string, projectId: string, name: string, description: string) {
        try {
            const data = await makeHttpRequest<{ fromListId: string, toListId: string, projectId: string, name: string, description: string }, { message: string }>(
                `card/${cardId}`,
                'PUT',
                {
                    fromListId: listId,
                    toListId: listId,
                    projectId: projectId,
                    name: name,
                    description: description
                }
            );
            toastNotification.showSuccess(data.message);
        } catch (error) {
            cardActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    return { cardActionLoading, createCard, deleteCard, reorderCard, editCard }
}
