import { ref } from "vue"
import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import utility from "../../../../helper/utility";

export type ProjectType = {
    id: number,
    name: string,
    startDate: string,
    endDate: string,
    slug: string,
    task_progress: {
        id: number,
        projectId: number,
        progress: string,
        created_at: string,
        updated_at: string,
    }
}

export type GetProjectType = {
    data: {
        data: Array<ProjectType>
    }
} & Record<string, any>

export function useGetProjects() {
    const loading = ref(false);
    const projectData = ref<GetProjectType>({} as GetProjectType)
    async function getProjects(page: number = 1, query: string = '') {
        try {
            loading.value = true;
            const data = await makeHttpRequest<undefined, GetProjectType>(`project?page=${page}&query=${query}`, 'GET');
            loading.value = false;
            projectData.value = data;
        } catch (error) {
            loading.value = false;
            utility.showErrorMessage(error);
        }
    }
    return { getProjects, projectData, loading }
}