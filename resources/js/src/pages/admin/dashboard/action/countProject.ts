import { ref } from "vue"
import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import utility from "../../../../helper/utility";

type CountProjectType = {
    count: number,
}

export function useGetTotalProject() {
    // const loading = ref(false);
    const countProject = ref<CountProjectType>({} as CountProjectType);
    async function getTotalProject() {
        try {
            // loading.value = true;
            const data = await makeHttpRequest<undefined, CountProjectType>(`count/project`, 'GET');
            countProject.value = data;
            updateData();
            // loading.value = false;
        } catch (error) {
            // loading.value = false;
            utility.showErrorMessage(error);
        }
    }

    function updateData() {
        window.Echo.channel("countProject").listen(
            "ProjectCreated",
            (e: any) => {
                countProject.value={count:e.countProject}
                // console.log(e);
            }
        );
    }

    return { getTotalProject, countProject }
}