<script setup lang="ts">
import { onMounted, ref } from "vue";
import { MemberType, useMember } from "./action/useMember"
import { getUserData } from "@/helper/getUserData"

const props = defineProps<{
    showModal: boolean,
    projectId: string,
    memberData: MemberType
}>();

const emit = defineEmits<{
    (e: "closeModal"): void,
    (e: "syncDeleteMember", memberId: string): void
}>()

const { memberActionLoading, addMember, deleteMember } = useMember();

const email = ref("");

async function addMemberByEmail() {
    if (email.value === "") return;
    addMember(props.projectId, email.value, props.memberData);
}


async function deleteUser(memberId: string) {
    deleteMember(props.projectId, memberId, syncDeleteMember(memberId));
}

function syncDeleteMember(memberId: string) {
    props.memberData.data = props.memberData.data.filter(
        (m) => m.id != memberId
    )
}

function isOwner() {
    const data = getUserData();
    return data?.user.id === props.memberData.owner.id;
}

</script>
<template>
    <div v-if="showModal" class="fixed h-dvh top-0 right-0 left-0 overflow-hidden z-50">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
        <div class="flex justify-center items-center w-full h-full">
            <div class="relative bg-white w-full max-w-sm rounded-lg shadow-lg">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Member
                    </h3>
                    <button
                        @click="emit('closeModal')"
                        type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
                <div v-if="isOwner()" class="inline-flex justify-between w-full p-4 md:p-5 border-b space-x-2">
                    <div class="flex-1 relative h-8">
                        <input
                            v-model="email"
                            placeholder="Email"
                            class="border px-1 w-full h-full"
                        />
                        <!-- <div class="z-50 absolute top-8 left-0 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto">
                            <ul>
                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    <span>kevin@gmail.com</span>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                    
                    <button @click="addMemberByEmail" class="bg-gray-100 rounded p-1 hover:bg-gray-200 h-8">
                        Add member
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-4 md:p-5">
                    <ul class="space-y-6">

                        <li>
                            <div class="flex items-center gap-4 hover:bg-gray-100 group">
                                <div
                                    class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-blue-200 rounded-full"
                                >
                                    <span class="font-medium text-sm text-gray-600 pointer-events-none">{{ memberData.owner.email[0] }}</span>
                                </div>
                                <div class="items-center">
                                    <div class="text-sm font-medium pl-1">{{ memberData.owner.email }}</div>

                                    <div class="">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Admin</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        <li v-for="(member, index) in memberData?.data">
                            <div class="flex items-center gap-4 hover:bg-gray-100 group">
                                <div
                                    class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-blue-200 rounded-full"
                                >
                                    <span class="font-medium text-sm text-gray-600 pointer-events-none">{{ member.email[0] }}</span>
                                </div>
                                <div class="items-center">
                                    <div class="text-sm font-medium pl-1">{{ member.email }}</div>
                                </div>
                                <!-- <svg 
                                v-if="memberActionLoading"
                                aria-hidden="true" role="status" class="inline w-4 h-4 text-blue animate-spin ms-auto me-2" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB" />
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor" />
                                </svg> -->
                                <div
                                v-if="isOwner()"
                                class="flex ms-auto items-center">
                                    <button
                                        @click="deleteUser(member.id)"
                                        type="button"
                                        class="text-gray-400 bg-transparent hidden group-hover:inline-flex hover:text-gray-900 rounded-lg text-sm w-8 h-8 justify-center items-center">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>