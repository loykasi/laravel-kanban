import { ref } from "vue"
import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import utility from "../../../../helper/utility";
import { getUserData } from "@/helper/getUserData";

export type ProjectType = {
    id: string,
    name: string,
    startDate: string,
    endDate: string,
    slug: string,
    updated_at: string,
    userId: string,
    task_progress: {
        id: number,
        projectId: number,
        progress: string,
        created_at: string,
        updated_at: string,
    }
}

export type GetProjectType = {
    data: Array<ProjectType>
} & Record<string, any>

export function useGetProjects() {
    const loading = ref(false);
    const projectData = ref<GetProjectType>({} as GetProjectType)
    const collabProjectData = ref<GetProjectType>({} as GetProjectType)

    async function getProjects(page: number = 1, query: string = '') {
        try {
            const userData = getUserData();

            loading.value = true;
            const data = await makeHttpRequest<{ userId: string | undefined }, GetProjectType>(
                            `user/${userData?.user.id}/project`, 
                            'GET');
            loading.value = false;
            projectData.value = data;
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }

    async function getCollabProjects(page: number = 1, query: string = '') {
        try {
            const userData = getUserData();

            loading.value = true;
            const data = await makeHttpRequest<{ userId: string | undefined }, GetProjectType>(
                            `user/${userData?.user.id}/collab/project`, 
                            'GET');
            loading.value = false;
            collabProjectData.value = data;
        } catch (error) {
            loading.value = false;
            // utility.showErrorMessage(error);
        }
    }

    return { getProjects, getCollabProjects, projectData, collabProjectData, loading }
}