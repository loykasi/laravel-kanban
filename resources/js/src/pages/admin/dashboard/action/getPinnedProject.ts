import { ref } from "vue"
import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import utility from "../../../../helper/utility";

type PinnedProject = { 
    id: number, 
    name: string 
}

export type PinnedProjectType = {
    data: PinnedProject
}

export function useGetPinnedProject() {
    // const loading = ref(false);
    const project = ref<PinnedProject>({} as PinnedProject);
    async function getPinnedProject() {
        try {
            // loading.value = true;
            const {data} = await makeHttpRequest<undefined, PinnedProjectType>(`pin/project`, 'GET');
            project.value = data;
            // loading.value = false;
        } catch (error) {
            // loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { getPinnedProject, project }
}