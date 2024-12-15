import { ref } from 'vue';
import { makeHttpRequest } from "@/helper/makeHttpRequest";
import toastNotification from "@/helper/toastNotification";
import utility from "@/helper/utility";

export type MemberType = {
    owner: {
        id: string,
        email: string,
    },
    data: {
        id: string,
        email: string,
    }[]
}

export function useMember() {
    const memberActionLoading = ref(false);
    const memberData = ref<MemberType>({} as MemberType)

    async function getProjectMember(projectId: string) {
        try {
            const data = await makeHttpRequest<{ id: string }, MemberType>(
                `member/${projectId}`,
                'GET',
            );
            memberData.value = data;
        } catch (error) {
            memberActionLoading.value = false;
        }
    }

    async function addMember(projectId: string, email: string, memberData: MemberType) {
        try {
            const data = await makeHttpRequest<{ projectId: string, email: string }, any>(
                `member`,
                'POST',
                {
                    projectId: projectId,
                    email: email
                }
            );
            memberData.data.push({
                id: data.member.id,
                email: data.member.email
            });
        } catch (error) {
            memberActionLoading.value = false;
            utility.showErrorMessage(error);
        }
    }

    async function deleteMember(projectId: string, memberId: string, callback: void) {
        try {
            memberActionLoading.value = true;
            const data = await makeHttpRequest<{ projectId: string, memberId: string }, any>(
                `member`,
                'DELETE',
                {
                    projectId: projectId,
                    memberId: memberId
                }
            );
            callback;
        } catch (error) {
            memberActionLoading.value = false;
        }
    }

    return { memberActionLoading, memberData, getProjectMember, addMember, deleteMember }
}