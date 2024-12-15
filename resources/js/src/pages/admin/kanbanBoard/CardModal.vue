<script setup lang="ts">
import { onMounted, ref } from "vue";
import { CardType } from './action/getProjectDetail.types';
import { useCard, CreateCardInput } from './action/useCard';

const props = defineProps<{
    showModal: boolean,
    projectId: string,
    card: CardType | undefined
}>();

const emit = defineEmits<{
    (e: "closeModal"): void,
    (e: "syncDeleteCard", card: CardType): void,
}>()

const { cardActionLoading, editCard, deleteCard } = useCard();
const isEditDescription = ref(false);
const description = ref("");

const newCardTitle = ref("");

function isInvalidDescription(): boolean {
    if (props.card == null) return true;
    if (props.card.description == null || props.card.description === "") return true;
    return false;
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

</script>
<template>
    <div v-if="showModal" class="fixed h-dvh top-0 right-0 left-0 overflow-hidden z-50">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
        <div class="flex justify-center items-center w-full h-full">
            <div class="relative bg-white w-full max-w-md rounded-lg shadow-lg">
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
                <div class="px-4 py-2 md:p-5 border-b">
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
                <div class="p-4 md:p-5">
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
</template>