import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import { ref } from 'vue';
import toastNotification from "../../../../helper/toastNotification";
import utility from "../../../../helper/utility";
import { projectStore } from "../../../../store/projectStore";

export type ProjectInputType = {
    id: number,
    name: string,
    startDate: string,
    endDate: string,
}

export type ProjectResponseType = {
    message: string
}

// export const memberInput = ref<ProjectInputType>({} as ProjectInputType)

export function useProjectFunction() {
    const loading = ref(false);
    async function createProject() {
        try {
            loading.value = true;
            const data = projectStore.edit? await updateProject() : await addProject();
            loading.value = false;
            projectStore.projectInput = {} as ProjectInputType;
            toastNotification.showSuccess(data.message);
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { createProject, loading }
}

async function addProject()
{
    const data = await makeHttpRequest<ProjectInputType, ProjectResponseType>('project', 'POST', projectStore.projectInput);
    return data;
}

async function updateProject()
{
    const data = await makeHttpRequest<ProjectInputType, ProjectResponseType>('project', 'PUT', projectStore.projectInput);
    projectStore.edit = false;
    return data;
}