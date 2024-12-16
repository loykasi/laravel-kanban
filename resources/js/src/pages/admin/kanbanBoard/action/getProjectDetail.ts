import { ref } from "vue"
import { makeHttpRequest } from "@/helper/makeHttpRequest";
import utility from "@/helper/utility";
import { SingleProjectResponseType } from "./getProjectDetail.types";

export function useGetProjectDetail() {
    const loading = ref(false);
    const projectDetail = ref<SingleProjectResponseType>({} as SingleProjectResponseType)
    async function getProjectDetail(id: string) {
        try {
            loading.value = true;
            const data = await makeHttpRequest<undefined, SingleProjectResponseType>(`project/${id}`, 'GET');
            loading.value = false;
            data.data.lists.sort((a, b) => a.order - b.order);
            data.data.lists.map((list) => {
                list.cards.sort((a, b) => a.order - b.order);
            })
            data.data.lists.forEach((item) => {
                item.isEditing = false;
                item.isCreating = false;
                item.cards.forEach((item) => {
                    item.isEditing = false;
                });
            });
            projectDetail.value = data;
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { loading, projectDetail, getProjectDetail }
}