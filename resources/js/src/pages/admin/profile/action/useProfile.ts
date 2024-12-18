import { ref } from "vue";
import { makeHttpRequest } from "@/helper/makeHttpRequest";
import { getUserData } from "@/helper/getUserData";

export type UserType = {
    email: string,
    // joined_at: string
}

export function useProfile() {
    const loading = ref(false);
    const accountData = ref<UserType>({} as UserType)
    
    async function getProfile(id: string) {
        const userData = getUserData();

        if (userData?.user.id === id) {
            accountData.value = {
                email: userData.user.email,
            }
            return;
        }
        try {
            loading.value = true;
            const data = await makeHttpRequest<undefined, UserType>(
                            `user/${id}`, 
                            'GET');
            loading.value = false;
            accountData.value = data;
        } catch (error) {
            loading.value = false;
        }
    }

    return { accountData, getProfile }
}