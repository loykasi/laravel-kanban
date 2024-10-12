import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import { ref } from 'vue';
import toastNotification from "../../../../helper/toastNotification";
import utility from "../../../../helper/utility";
import { memberStore } from "../../../../store/memberStore";

export type MemberInputType = {
    name: string,
    email: string,
}

export type MemberResponseType = {
    message: string
}

// export const memberInput = ref<MemberInputType>({} as MemberInputType)

export function useMemberFunction() {
    const loading = ref(false);
    async function createMember() {
        try {
            loading.value = true;
            const data = memberStore.edit? await updateMember() : await addMember();
            loading.value = false;
            memberStore.memberInput = {} as MemberInputType;
            toastNotification.showSuccess(data.message);
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { createMember, loading }
}

async function addMember()
{
    const data = await makeHttpRequest<MemberInputType, MemberResponseType>('member', 'POST', memberStore.memberInput);
    return data;
}

async function updateMember()
{
    const data = await makeHttpRequest<MemberInputType, MemberResponseType>('member', 'PUT', memberStore.memberInput);
    memberStore.edit = false;
    return data;
}