<script lang="ts" setup>
import { onMounted, ref } from "vue";
import ApexDonut from "../../../components/ApexDonut.vue";
import ApexRadialBar from "../../../components/ApexRadialBar.vue";
import { useGetPinnedProject } from "./action/getPinnedProject";
import { useGetTotalProject } from "./action/countProject";
import { useGetChartData } from "./action/getChartData";

const { project, getPinnedProject } = useGetPinnedProject();
const { countProject, getTotalProject } = useGetTotalProject();
const { chartData, getChartData } = useGetChartData();

onMounted(async () => {
    await getPinnedProject();
    await getTotalProject();
    await getChartData(project.value.id);
});
</script>
<template>
    <div class="row">
        <h2>Dashbaord</h2>
        <br />
        <br />
        <br />
        <div class="row">
            <div class="col-md-8">
                <h3 style="color: rgb(118, 119, 120)">
                    Project :{{ project?.name }}
                </h3>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <b>Total Projects</b>
                    </div>
                    <div class="card-body">
                        <br />
                        <br />
                        <h2 align="center">{{ countProject?.count }}</h2>
                        <br />
                        <br />
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header"><b>Tasks</b></div>
                    <div class="card-body">
                        
                        <div v-if="chartData.tasks">
                            <ApexDonut :task="chartData.tasks" />
                        </div>
                        <div v-else>
                            <h4>No tasks</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <b>Task Progress</b>
                    </div>

                    <div class="card-body">
                        <div v-if="chartData.progress > 0">
                            <ApexRadialBar :percent="chartData.progress" />
                        </div>
                        <div v-else>
                            <h4>No tasks</h4>
                        </div>
                        <br />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>