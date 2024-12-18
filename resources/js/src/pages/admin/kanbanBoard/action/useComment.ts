import { ref } from 'vue';
import { makeHttpRequest } from "@/helper/makeHttpRequest";
import toastNotification from "@/helper/toastNotification";
import utility from "@/helper/utility";

export type Comment = {
    id: string,
    content: string,
    card_id: string,
    user_id: string,
    created_at: string,
    updated_at: string,
}

export type GetCommentType = {
    comments: Comment[]
}

export type PostCommentType = {
    comment: Comment,
    message: string
}

export function useComment() {

    const commentData = ref<GetCommentType | null>({} as GetCommentType)

    async function getComments(cardId: string) {
        try {
            const data = await makeHttpRequest<undefined, GetCommentType>(
                `card/${cardId}/comment`,
                'GET',
            );
            commentData.value = data;
        } catch (error) {
            console.log(error);
        }
    }

    async function addComment(cardId: string, userId: string, content: string, callback?: (result: Comment) => void) {
        try {
            const data = await makeHttpRequest<{ userId: string, content: string }, PostCommentType>(
                `card/${cardId}/comment`,
                'POST',
                {
                    userId: userId,
                    content: content
                }
            );
            if (callback)
                callback(data.comment);
        } catch (error) {
            console.log(error);
        }
    }

    async function deleteComment(commentId: string) {
        try {
            const data = await makeHttpRequest<{ userId: string, content: string }, undefined>(
                `comment/${commentId}`,
                'DELETE',
            );
        } catch (error) {
            console.log(error);
        }
    }

    return { commentData, getComments, addComment, deleteComment }
}