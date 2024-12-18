<script setup lang="ts">
import { onMounted, ref, watch, onBeforeMount, onBeforeUnmount } from "vue";
import { CardType } from './action/getProjectDetail.types';
import { useCard, CreateCardInput } from './action/useCard';
import { Comment, GetCommentType, useComment } from './action/useComment';
import { MemberType } from "./action/useMember"
import { getUserData } from "@/helper/getUserData"
import { CardCommentCreatedType, CardCommentDeletedType } from "./types/comment.types"

const props = defineProps<{
    showModal: boolean,
    projectId: string,
    card: CardType | undefined,
    memberData: MemberType
}>();

const emit = defineEmits<{
    (e: "closeModal"): void,
    (e: "syncDeleteCard", card: CardType): void,
}>()

const { commentData, getComments, addComment, deleteComment } = useComment();
const { cardActionLoading, editCard, deleteCard } = useCard();
const isEditDescription = ref(false);
const isComment = ref(false);
const description = ref("");
const commentContent = ref("");
const newCardTitle = ref("");
const userId = ref("");

function isInvalidDescription(): boolean {
    if (props.card == null) return true;
    if (props.card.description == null || props.card.description === "") return true;
    return false;
}

function isInvalidCreateDate(value: string): boolean {
    return value === '';
}

function isOwner(targetUserId: string) {
    return userId.value === targetUserId;
}

function openComment() {
    commentContent.value = "";
    isComment.value = true;
}

function closeComment() {
    isComment.value = false;
}

async function syncAddComment() {
    if (props.card == null) return;

    const count = commentData.value!.comments.push({
        id: "",
        content: commentContent.value,
        card_id: props.card.id,
        user_id: userId.value,
        created_at: "",
        updated_at: "",
    });
    const index = count - 1;

    addComment(props.card.id, userId.value, commentContent.value, (result) => {
        const comment = commentData.value!.comments[index];
        comment.id = result.id;
        comment.created_at = result.created_at;
        comment.updated_at = result.updated_at;
    });
    commentContent.value = "";
}

async function syncDeleteComment(commentId: string) {
    if (commentId === "") return;

    commentData.value!.comments = commentData.value!.comments.filter(
        c => c.id != commentId
    );
    deleteComment(commentId);
}

function openEditDescription() {
    if (props.card == null) return;

    isEditDescription.value = true;

    if (isInvalidDescription()) {
        description.value = "";
    } else {
        description.value = props.card.description;
    }
}

function closeEditDescription() {
    isEditDescription.value = false;
    if (props.card == null) return;
}

function updateDescription() {
    closeEditDescription();
    if (props.card == null) return;
    props.card.description = description.value;
    editCard(props.card.id, props.card.listId, props.projectId, props.card.name, description.value);
}

function openEditCardTitle() {
    if (props.card == null) return;

    newCardTitle.value = props.card.name;
    props.card.isEditing = true;
}

function updateCardTitle() {
    if (props.card == null) return;
    props.card.isEditing = false;

    props.card.name = newCardTitle.value;
    editCard(props.card.id, props.card.listId, props.projectId, newCardTitle.value, props.card.description);
}

function deleteCardTitle() {
    if (props.card == null) return;

    emit('syncDeleteCard', props.card);
    clearAndCloseModel();
}

function clearAndCloseModel() {
    emit('closeModal')

    isEditDescription.value = false;    
    if (props.card == null) return;
    props.card.isEditing = false;
}

onMounted(async () => {
    const data = getUserData();
    if (data == null) return;
    userId.value = data.user.id;
})

watch(() => props.showModal, (value) => {
    commentData.value = null;
    if (props.card == null) return;
    if (value) {
        getComments(props.card.id);
    }
})

function formatTimestamp(timestamp: string) {
  const date = new Date(timestamp);
  return date.toLocaleString();
}

function getUser(userId: string) {
    if (props.memberData.owner.id === userId) {
        return props.memberData.owner;
    }
    return props.memberData.data.find((m) => m.id === userId);
}

// realtime
async function addCommentFromChanmel(e: CardCommentCreatedType) {
    const comment = {
        id: e.comment.id,
        content: e.comment.content,
        card_id: e.comment.card_id,
        user_id: e.comment.user_id,
        created_at: e.comment.created_at,
        updated_at: e.comment.updated_at,
    }

    commentData.value?.comments.push(comment);
}

async function deleteCommentFromChannel(e: CardCommentDeletedType) {
    commentData.value!.comments = commentData.value!.comments.filter(
        c => c.id != e.commentId
    )
}

watch(() => props.showModal, (value) => {
    if (!value) {
        window.Echo.leave(`card.${props.card!.id}`);
    } else {
    window.Echo.private(`card.${props.card!.id}`)
        .listen('CardCommentCreated', (e: CardCommentCreatedType) => {
            addCommentFromChanmel(e)
        })
        .listen('CardCommentDeleted', (e: CardCommentDeletedType) => {
            deleteCommentFromChannel(e)
        })        
    }
})

</script>
<template>
    <div v-if="showModal" class="fixed h-dvh top-0 right-0 left-0 overflow-hidden z-50">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
        <div class="flex justify-center items-center w-full h-full">
            <div class="relative bg-white w-full max-w-lg rounded-lg shadow-lg">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <div>
                        <h3
                            v-if="!card?.isEditing"
                            class="text-xl font-semibold text-gray-900 px-2"
                            @click="openEditCardTitle"
                        >
                            {{ card?.name }}
                        </h3>
                        <input
                            v-if="card?.isEditing"
                            v-model="newCardTitle"
                            type="text"
                            class="text-xl font-semibold px-2 text-gray-900 rounded-md resize-none bg-gray-200" 
                            @keydown.enter="updateCardTitle"
                        />
                    </div>
                    <button
                        @click="clearAndCloseModel"
                        type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
                <div class="flex">
                    <div class="flex-1">
                        <div class="p-5">
                            <div class="font-medium px-2 mb-3">Description</div>
                            <div v-if="!isEditDescription" @click="openEditDescription" class="hover:cursor-pointer">
                                <div
                                    v-if="isInvalidDescription()"
                                    class="bg-gray-100 hover:bg-gray-200 px-2 py-3 rounded"
                                >
                                    Add more detailed description...
                                </div>
                                <div v-else class="px-2 py-3 hover:bg-gray-100 whitespace-pre-line" readonly>
                                    {{ card?.description }}
                                </div>
                            </div>
                            <div v-else>
                                <textarea
                                    v-model="description"
                                    class="w-full h-60 px-2 py-3 resize-none border bg-gray-100"
                                >
                                </textarea>
                                <div class="space-x-2">
                                    <button
                                        @click="updateDescription"
                                        class="bg-blue-200 px-2 py-1 rounded"
                                    >
                                        Save
                                    </button>
                                    <button
                                        @click="closeEditDescription"
                                        class="bg-gray-200 px-2 py-1 rounded"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="font-medium px-2 mb-3">Comments</div>
                            <div
                                v-if="!isComment"
                                @click="openComment"
                                class="bg-gray-100 hover:bg-gray-200 px-2 py-2 rounded-md text-gray-500"
                            >
                                Write a comment...
                            </div>
                            <div v-else>
                                <textarea
                                    v-model="commentContent"
                                    class="w-full px-2 py-3 resize-none border bg-gray-100"
                                >
                                </textarea>
                                <div class="space-x-2">
                                    <button
                                        @click="syncAddComment"
                                        class="bg-blue-200 px-2 py-1 rounded"
                                    >
                                        Save
                                    </button>
                                    <button
                                        @click="closeComment"
                                        class="bg-gray-200 px-2 py-1 rounded"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </div>

                            <ul>
                                <li v-for="(comment, index) in commentData?.comments">
                                    <div class="mt-3 rounded-md space-y-1">
                                        <div class="px-2 space-x-2">
                                            <span class="font-medium">{{ getUser(comment.user_id)?.email }}</span>
                                            <span v-if="isInvalidCreateDate(comment.created_at)" class="text-gray-500 text-sm">Sending</span>
                                            <span v-else class="text-gray-500 text-sm">{{ formatTimestamp(comment.created_at) }}</span>
                                        </div>
                                        <div class="px-2 py-1 bg-gray-100 rounded-md shadow">
                                            {{ comment.content }}
                                        </div>
                                        <div class="px-2 text-sm text-gray-500">
                                            <button
                                                v-if="isOwner(comment.user_id)"
                                                @click="syncDeleteComment(comment.id)"
                                                class="hover:underline"
                                            >Delete</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <div class="pl-3 pr-5 py-5">
                            <div class="font-medium px-2 mb-3">Actions</div>
                            <button
                                @click="deleteCardTitle"
                                class="bg-gray-200 px-2 py-1 rounded mx-2 hover:bg-gray-300 hover:cursor-pointer"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>