import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import { ref } from 'vue';
import toastNotification from "../../../../helper/toastNotification";
import utility from "../../../../helper/utility";

export type MemberType = {
    id: number,
    name: string,
    email: string
}

export type GetMemberType = {
    data: {
        data: Array<MemberType>
    }
} & Record<string, any>

export function useGetMembers() {
    const loading = ref(false);
    const memberData = ref<GetMemberType>({} as GetMemberType)
    async function getMembers(page: number = 1, query: string = '') {
        try {
            loading.value = true;
            const data = await makeHttpRequest<undefined, GetMemberType>(`member?page=${page}&query=${query}`, 'GET');
            loading.value = false;
            memberData.value = data;
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { getMembers, memberData, loading }
}