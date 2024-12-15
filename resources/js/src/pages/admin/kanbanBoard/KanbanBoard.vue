<script setup lang="ts">
import { onBeforeMount, onBeforeUnmount, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useGetProjectDetail } from './action/getProjectDetail';
import { useList, CreateListInput } from './action/useList';
import { useCard, CreateCardInput } from './action/useCard';
import { useProject } from './action/useProject';
import { MemberType, useMember } from "./action/useMember"
import { CardType, ListType } from './action/getProjectDetail.types';
import { ListCreatedType, ListDeletedType, ListUpdatedType } from './types/list.types';
import { CardUpdatedType, CardDeletedType, CardCreatedType } from './types/card.types'
import MemberModal from './MemberModel.vue'
import CardModal from './CardModal.vue'

import utility from '@/helper/utility';
import { taskStore } from '@/store/kanbanStore';
import { useDragTask } from './action/dragTask';
import { watch } from 'vue';

const route = useRoute();
const { loading, projectDetail, getProjectDetail } = useGetProjectDetail();
const { projectActionLoading, updateProjectTitle } = useProject();
const { listActionLoading, createList, deleteList, reorderList, editList } = useList();
const { cardActionLoading, createCard, reorderCard, deleteCard } = useCard();
const { memberData, getProjectMember } = useMember();

const isShowCreateList = ref(false);
const newListName = ref('');
const editListTitle = ref('');

const newCardName = ref('');

const newProjectName = ref('');
const isEditProjectTitle = ref(false);

const isShowMemberModel = ref(false);

const isShowCardModel = ref(false);
const selectCard = ref<CardType | undefined>();


// card model

async function openCardModal(card: CardType) {
    selectCard.value = card;
    isShowCardModel.value = true;
}

async function closeCardModel() {
    isShowCardModel.value = false;
}

// member model

async function openMemberModel() {
    isShowMemberModel.value = true;
}

async function closeMemberModel() {
    isShowMemberModel.value = false;
}


// project

function openEditProjectTitle() {
    newProjectName.value = projectDetail.value.data.name;
    isEditProjectTitle.value = true;
}

function updateProjectTile() {
    isEditProjectTitle.value = false;
    if (projectDetail.value.data.name === newProjectName.value) return;
    projectDetail.value.data.name = newProjectName.value;
    updateProjectTitle(projectDetail.value.data.id, newProjectName.value);
}

// list

function openCreateList() {
    newListName.value = "";
    isShowCreateList.value = true;
}

function closeCreateList() {
    isShowCreateList.value = false;
}

function syncCreateList() {
    const listInput: CreateListInput = {
        name: newListName.value,
        projectId: projectDetail.value.data.id
    }

    closeCreateList();
    const list = {
        id: "",
        projectId: projectDetail.value.data.id,
        name: newListName.value,
        order: projectDetail.value.data.lists.length,
        isCreating: false,
        isEditing: false,
        cards: []
    };
    projectDetail.value.data.lists.push(list);

    createList(listInput, (result) => {
        list.id = result.id
    });
}

function syncDeleteList(listId: string) {
    deleteList(listId, projectDetail.value.data.id);
    projectDetail.value.data.lists = projectDetail.value.data.lists.filter(
        (list) => list.id !== listId
    )
}

function openEditListTitle(listId: string) {
    const list = projectDetail.value.data.lists.find((list) => list.id === listId);
    if (list == null) return;

    editListTitle.value = list.name;
    list.isEditing = true;
}

function updateListTitle(listId: string) {
    const list = projectDetail.value.data.lists.find((list) => list.id === listId);
    if (list == null) return;

    list.isEditing = false;
    if (editListTitle.value === list.name) return;

    list.name = editListTitle.value
    editList(listId, projectDetail.value.data.id, editListTitle.value);
}


// drag and drop

const saveIndex = ref(0);
const dragId = ref('');
const isDragging = ref(false);
const dragType = ref(0);
const listDragFrom = ref<ListType | null>(null);

function onStartDragList(id: string, index: number) {
    if (isDragging.value) return;

    isDragging.value = true;
    saveIndex.value = index;
    dragId.value = id;
    dragType.value = 0;
}

function onEndDragList(index: number) {
    if (dragType.value != 0) return;

    if (!isDragging.value) return;
    isDragging.value = false;

    console.log("list");

    if (saveIndex.value == index) {
        return;
    }
    
    reorderList(dragId.value, projectDetail.value.data.id, index);

    const [item] = projectDetail.value.data.lists.splice(saveIndex.value, 1);
    projectDetail.value.data.lists.splice(index, 0, item);

    projectDetail.value.data.lists.forEach((item, index) => {
        item.order = index;
    });
}

function onStartDragCard(id: string, index: number, list: ListType) {
    if (isDragging.value) return;

    isDragging.value = true;
    saveIndex.value = index;
    dragId.value = id;
    listDragFrom.value = list;
    dragType.value = 1;
}

function onEndDragCard(index: number, targetList: ListType) {
    if (dragType.value != 1) return;

    // console.log("1. " + saveIndex.value + " -> " + index);

    if (!isDragging.value) return;
    isDragging.value = false;

    if (targetList == null || listDragFrom.value == null) return;

    if (saveIndex.value == index && targetList.id == listDragFrom.value.id) return;

    reorderCard(dragId.value, listDragFrom.value.id, targetList.id, projectDetail.value.data.id, index);

    // console.log("2. " + saveIndex.value + " -> " + index);

    const [item] = listDragFrom.value.cards.splice(saveIndex.value, 1);
    targetList.cards.splice(index, 0, item);
    item.listId = targetList.id;

    targetList.cards.forEach((item, index) => {
        item.order = index;
    });
    if (targetList.id != listDragFrom.value.id) {
        listDragFrom.value.cards.forEach((item, index) => {
            item.order = index;
        });
    }
}

// card
const currentCreatingList = ref<ListType | null>()

function openCreateCard(list: any) {
    newCardName.value = "";
    if (currentCreatingList.value != null) {
        currentCreatingList.value.isCreating = false;
    }
    currentCreatingList.value = list;
    list.isCreating = true
}

function closeCreateCard(list: any) {
    currentCreatingList.value = null;
    list.isCreating = false
}

function syncCreateCard() {
    if (currentCreatingList.value != null) {
        currentCreatingList.value.isCreating = false;
    }
    if (newCardName.value == "") return;
    if (currentCreatingList.value == null) return;

    const listId = currentCreatingList.value.id;
    const cardInput: CreateCardInput = {
        name: newCardName.value,
        listId: listId,
        projectId: projectDetail.value.data.id
    }

    const list = projectDetail.value.data.lists.find((u) => u.id == listId);
    if (list == null) return;
    const card: CardType = {
        id: "",
        listId: listId,
        name: newCardName.value,
        order: list.cards.length,
        isEditing: false,
        description: ""
    }
    
    list.cards.push(card);

    createCard(cardInput, (result) => {
        card.id = result.id
    });
}

function syncDeleteCard(card: CardType) {
    const listId = card.listId;
    const list = projectDetail.value.data.lists.find((u) => u.id == listId);
    if (list == null) return;

    list.cards = list.cards.filter(
        (c) => c.id !== card.id
    )
    deleteCard(card.id, projectDetail.value.data.id);
}


///

function addFromChannel(listCreated: ListCreatedType) {
    const list = {
        id: listCreated.list.id,
        projectId: listCreated.list.projectId,
        name: listCreated.list.name,
        order: listCreated.list.order,
        isCreating: false,
        isEditing: false,
        cards: []
    };
    projectDetail.value.data.lists.push(list);
}

function deleteFromChannel(listDeleted: ListDeletedType) {
    projectDetail.value.data.lists = projectDetail.value.data.lists.filter(
        (list) => list.id !== listDeleted.listId
    )
}

function updateFromChannel(listUpdated: ListUpdatedType) {
    const list = projectDetail.value.data.lists.find((u) => u.id == listUpdated.list.id);

    if (list == null)
        return;

    list.name = listUpdated.list.name;

    if (list.order != listUpdated.list.order) {
        const oldOrder = list.order;
        const newOrder = listUpdated.list.order;
        
        const [item] = projectDetail.value.data.lists.splice(oldOrder, 1);
        projectDetail.value.data.lists.splice(newOrder, 0, item);

        projectDetail.value.data.lists.forEach((item, index) => {
            item.order = index;
        });
    }    
}

function updateCardFromChannel(cardUpdated: CardUpdatedType) {
    console.log(cardUpdated);

    const fromList = projectDetail.value.data.lists.find((u) => u.id == cardUpdated.fromListId);
    const toList = projectDetail.value.data.lists.find((u) => u.id == cardUpdated.card.listId);
    const card = fromList?.cards.find((c) => c.id == cardUpdated.card.id);

    if (card == null || fromList == null || toList == null) return;

    card.name = cardUpdated.card.name;
    card.description = cardUpdated.card.description;
    card.listId = cardUpdated.card.listId;

    if (card.order != cardUpdated.card.order || (card.order == cardUpdated.card.order && fromList.id != toList.id)) {
        const [item] = fromList.cards.splice(card.order, 1);
        toList.cards.splice(cardUpdated.card.order, 0, item);

        toList.cards.forEach((item, index) => {
            item.order = index;
        });
        if (fromList.id != toList.id) {
            fromList.cards.forEach((item, index) => {
                item.order = index;
            });
        }
    }
}

function deleteCardFromChannel(cardDeleted: CardDeletedType) {
    const list = projectDetail.value.data.lists.find((u) => u.id === cardDeleted.listId);
    if (list == null) return;
    list.cards = list.cards.filter(
        (card) => card.id !== cardDeleted.cardId
    )
}

function addCardFromChannel(cardCreated: CardCreatedType) {
    const list = projectDetail.value.data.lists.find((u) => u.id === cardCreated.card.listId);
    if (list == null) return;

    const card = {
        id: cardCreated.card.id,
        listId: cardCreated.card.listId,
        name: cardCreated.card.name,
        description: cardCreated.card.description,
        order: cardCreated.card.order,
        isCreating: false,
        isEditing: false,
    };
    list.cards.push(card);
}

onBeforeMount(() => {
    window.Echo.private(`project.${route.params.id}`)
        .listen('CardListCreated', (e: ListCreatedType) => {
            addFromChannel(e)
        })
        .listen('CardListDeleted', (e: ListDeletedType) => {
            deleteFromChannel(e)
        })
        .listen('CardListUpdated', (e: ListUpdatedType) => {
            updateFromChannel(e)
        })
        .listen('CardUpdated', (e: CardUpdatedType) => {
            updateCardFromChannel(e);
        })
        .listen('CardCreated', (e: CardCreatedType) => {
            addCardFromChannel(e);
        })
        .listen('CardDeleted', (e: CardDeletedType) => {
            deleteCardFromChannel(e);
        })
})

onMounted(async ()=>{
    let projectId = route.params.id as string;
    getProjectDetail(projectId);
    getProjectMember(projectId);
})

onBeforeUnmount(() => {
    window.Echo.leaveChannel(`project.${route.params.id}`);
})
</script>
<template>
    <div class="flex flex-col h-full">
        <div class="flex justify-between bg-gray-50 px-4 py-2 mb-2">
            <div>
                <div v-if="!isEditProjectTitle" class="leading-6 text-xl font-medium" @click="openEditProjectTitle">
                    {{ projectDetail.data?.name }}
                </div>
                <input
                    v-if="isEditProjectTitle"
                    v-model="newProjectName"
                    type="text"
                    class="leading-6 text-xl font-medium rounded-md resize-none bg-gray-200" 
                    @keydown.enter="updateProjectTile"
                />
            </div>
            <div class="flex">
                <div class="space-x-2">              
                    <div
                        v-for="(member, index) in memberData?.data"
                        class="relative inline-flex items-center justify-center w-6 h-6 overflow-hidden bg-gray-200 rounded-full"
                    >
                        <span class="font-medium text-sm text-gray-600 pointer-events-none">{{ member.email[0] }}</span>
                    </div>
                </div>
                <button
                    @click="openMemberModel"
                    class="ml-3 bg-gray-300 rounded px-2 hover:bg-gray-200 hover:cursor"
                >
                    Member
                </button>
            </div>
        </div>
        <ul class="flex flex-1 overflow-x-auto">
            <li v-for="(list, index) in projectDetail.data?.lists"
                :key="list.id"
                @drop="onEndDragCard(0, list), onEndDragList(index)"
                @dragenter.prevent
                @dragover.prevent
                class="p-2"
            >
                <div
                    draggable="true"
                    @dragstart="onStartDragList(list.id, index)"
                    class="w-72 bg-gray-50 border rounded-xl shadow px-2 pb-2"
                >
                    <div class="inline-flex justify-between w-full items-center font-semibold text-sm px-3 py-3">
                        <div @click="openEditListTitle(list.id)">
                            <span v-if="!list.isEditing">{{ list.name }}</span>
                            <input
                                v-if="list.isEditing"
                                v-model="editListTitle"
                                type="text"
                                class="bg-gray-200" 
                                @keydown.enter="updateListTitle(list.id)"
                            />
                        </div>
                        <button
                        @click="syncDeleteList(list.id)"
                        class="font-semibold text-sm rounded-md text-gray-800 hover:bg-gray-200">
                            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                        </button>
                    </div>
                    <li>
                        <ul v-for="(card, index2) in list.cards"
                        @drop="onEndDragCard(index2, list)"
                        @dragenter.prevent
                        @dragover.prevent>
                                <div
                                draggable="true"
                                @dragstart="onStartDragCard(card.id, index2, list)"
                                class="bg-white border border-slate-200 rounded-xl shadow px-4 py-2 mb-2 hover:cursor-pointer"
                                @click="openCardModal(card)"
                                >{{ card.name }}</div>
                        </ul>
                    </li>
                    <div
                        v-if="!list.isCreating"
                        @click="openCreateCard(list)"
                        class="hover:cursor-pointer flex items-center font-semibold text-sm text-slate-700 px-2 py-1 rounded-xl hover:bg-gray-200">
                        <svg class="w-6 h-6 mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                        </svg>
                        <span>Add a card</span>
                    </div>
                    <div v-if="list.isCreating">
                        <textarea
                            v-model="newCardName"
                            placeholder="Enter title"
                            type="text"
                            class="font-semibold text-sm px-3 py-3 h-14 w-full bg-gray-300 rounded-md text-start resize-none" 
                        ></textarea>
                        <div class="flex items-center space-x-2">
                            <button
                            @click="syncCreateCard"
                            class="font-semibold text-sm text-slate-700 px-2 py-1 h-8 rounded-md bg-blue-300 hover:bg-blue-400">
                                <span>Add card</span>
                            </button>
                            <button
                            @click="closeCreateCard(list)"
                            class="font-semibold text-sm px-2 py-1 h-8 rounded-md text-gray-800 hover:bg-gray-200">
                                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </li>

            <!-- <li
                class="p-2"
            >
                <div
                    draggable="true"
                    class="w-72 bg-gray-50 border rounded-xl shadow px-2 pb-2"
                >
                    <div class="font-semibold text-sm px-3 py-3">Game Features (May Have)</div>
                    <div>
                        <div>
                            <div draggable="true" class="bg-white border border-slate-200 rounded-xl shadow px-4 py-2 mb-2">Card 1</div>
                        </div>
                        <div>
                            <div draggable="true" class="bg-white border border-slate-200 rounded-xl shadow px-4 py-2 mb-2">Card 1</div>
                        </div>
                        <div>
                            <div draggable="true" class="bg-white border border-slate-200 rounded-xl shadow px-4 py-2 mb-2">Unlock things by completing achievements</div>
                        </div>
                    </div>
                    <div class="flex items-center font-semibold text-sm text-slate-700 px-3 py-1 rounded-xl hover:bg-gray-200">
                        <svg class="w-6 h-6 mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                        </svg>
                        <span>Add a card</span>
                    </div>
                </div>
            </li> -->

            <div v-if="isShowCreateList" class="p-2">
                <div class="w-72 bg-gray-50 border rounded-xl shadow px-2 py-2">
                    <textarea 
                        v-model="newListName"
                        placeholder="Enter list name"
                        type="text"
                        class="font-semibold text-sm px-3 py-3 h-14 w-full bg-gray-300 rounded-md text-start resize-none" 
                    ></textarea>
                    <div class="flex items-center space-x-2">
                        <button
                        @click="syncCreateList"
                        class="font-semibold text-sm text-slate-700 px-2 py-1 h-8 rounded-md bg-blue-300 hover:bg-blue-400">
                            <span>Add list</span>
                        </button>
                        <button
                        @click="closeCreateList"
                        class="font-semibold text-sm px-2 py-1 h-8 rounded-md text-gray-800 hover:bg-gray-200">
                            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="!isShowCreateList" class="p-2">
                <div @click="openCreateList" class="hover:cursor-pointer w-72 bg-opacity-50 border rounded-xl shadow px-2 py-3 flex items-center font-semibold text-sm text-slate-700 hover:bg-gray-200">
                    <svg class="w-6 h-6 mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                    </svg>
                    <span>Add new list</span>
                </div>
            </div>
        </ul>
    </div>

    <MemberModal v-if="projectDetail?.data?.id"
        :show-modal="isShowMemberModel"
        :project-id="projectDetail.data.id"
        :member-data="memberData"
        @close-modal="closeMemberModel"
    />
    <CardModal v-if="projectDetail?.data?.id"
        @sync-delete-card="syncDeleteCard"
        @close-modal="closeCardModel"
        :show-modal="isShowCardModel"
        :card="selectCard"
        :project-id="projectDetail.data.id" />
</template>