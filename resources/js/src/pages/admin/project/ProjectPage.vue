<script setup lang="ts">
import { onMounted } from 'vue';
import { ProjectType, useGetProjects } from './action/getProject';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import ProjectTable from '../../../components/ProjectTable.vue';
import { useRouter } from 'vue-router';
import { projectStore } from '../../../store/projectStore';
import { usePinProject } from './action/pinProject';


const { getProjects, projectData, loading } = useGetProjects()
const { pinProject } = usePinProject()

const router = useRouter();

async function pinProjectToDashboard(projectId: number) {
    await pinProject(projectId);
    router.push('/admin');
}

function editProject(project: ProjectType) {
    projectStore.projectInput = project;
    projectStore.edit = true;
    router.push('/create-project')
}

async function showListOfProjects() {
    await getProjects();
}

onMounted(async ()=>{
    showListOfProjects();
    projectStore.projectInput = {} as ProjectType;
    projectStore.edit = false;
})
</script>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Projects
                        <RouterLink style="float:right" to="/create-project" class="btn btn-primary">Create Project</RouterLink>
                    </div>
                    <div class="card-body">
                        <ProjectTable @pinned-project="pinProjectToDashboard" @get-project="getProjects" @edit-project="editProject" :loading="loading" :projects="projectData">
                            <template #pagination>
                            <Bootstrap5Pagination v-if="projectData?.data" :data="projectData?.data" @pagination-change-page="getProjects" />
                            </template>
                        </ProjectTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>