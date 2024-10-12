import { ref } from "vue"
import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import utility from "../../../../helper/utility";
import { SingleProjectResponseType } from "./getProjectDetail.types";



export function useGetProjectDetail() {
    const loading = ref(false);
    const projectDetail = ref<SingleProjectResponseType>({} as SingleProjectResponseType)
    async function getProjectDetail(slug: string) {
        try {
            loading.value = true;
            const data = await makeHttpRequest<undefined, SingleProjectResponseType>(`project/${slug}`, 'GET');
            loading.value = false;
            projectDetail.value = data;
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { getProjectDetail, projectDetail, loading }
}