import { ref } from "vue"
import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import utility from "../../../../helper/utility";

type ChartDataType = { 
    tasks: Array<number>
    progress: number
}

export function useGetChartData() {
    const chartData = ref<ChartDataType>({} as ChartDataType);
    async function getChartData(projectId: number) {
        try {
            const data = await makeHttpRequest<undefined, ChartDataType>(`project/chart/${projectId}`, 'GET');
            chartData.value = data;
            updateData();
        } catch (error) {
            utility.showErrorMessage(error);
        }
    }

    function updateData() {
        window.Echo.channel("updateProgress").listen(
            "UpdateProgress",
            (e: {progress: number}) => {
                chartData.value.progress = e.progress;
            }
        );
    }

    return { getChartData, chartData }
}