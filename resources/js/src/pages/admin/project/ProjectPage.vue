<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { ProjectType, useGetProjects } from './action/getProject';
import { useRouter } from 'vue-router';
import { projectStore } from '../../../store/projectStore';
import { usePinProject } from './action/pinProject';
import { getUserData } from '@/helper/getUserData';
import CreateProject from './components/CreateProject.vue'


const { getProjects, getCollabProjects, projectData, collabProjectData, loading } = useGetProjects()
const { pinProject } = usePinProject()
const showModal = ref(false)

const router = useRouter();
const userData = getUserData();

function editProject(project: ProjectType) {
    projectStore.projectInput = project;
    projectStore.edit = true;
    router.push('/create-project')
}

async function showListOfProjects() {
    getProjects();
    getCollabProjects();
}

function getFormattedDate(value: string): string {
    const date = new Date(value);

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
}

function openCreateModal() {
    showModal.value = true;
}

function closeCreateModal() {
    showModal.value = false;
}

onMounted(async ()=>{
    console.log(`board.${userData?.user.id}`)
    window.Echo.channel(`board.${userData?.user.id}`).listen('ProjectCreated', (e: any) => {
            console.log(e);
        });
    showListOfProjects();
    projectStore.projectInput = {} as ProjectType;
    projectStore.edit = false;
})
</script>
<template>
    <div class="h-full w-full">
        <div class="container mx-auto h-full px-3">
            <div class="text-2xl font-semibold pt-2">
                Projects
            </div>
            <hr class="h-0.5 bg-black my-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2">
                <div @click="openCreateModal">
                    <a href="#" class="flex items-center justify-center h-full p-6 rounded-lg border-gray-300 border-2 bg-blue-100 hover:bg-blue-200">
                        <p class="mb-2 text-gray-600 tracking-tight">Create new project</p>
                    </a>
                </div>
                <div class="" v-for="project in projectData?.data" :key="project.id">
                    <RouterLink :to="'/project/' + project.id" class="block p-6 rounded-lg shadow-md border-gray-700 bg-gray-200 hover:bg-gray-300">
                        <p class="mb-2 font-bold tracking-tight">{{ project.name }}</p>
                        <span class="">Last updated: {{ getFormattedDate(project.updated_at) }}</span>
                    </RouterLink>
                </div>
            </div>
            <div class="text-2xl font-semibold pt-2 mt-10">
                Collaboration
            </div>
            <hr class="h-0.5 bg-black my-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2">
                <div class="" v-for="project in collabProjectData?.data" :key="project.id">
                    <RouterLink :to="'/project/' + project.id" class="block p-6 rounded-lg shadow-md border-gray-700 bg-gray-200 hover:bg-gray-300">
                        <p class="mb-2 font-bold tracking-tight">{{ project.name }}</p>
                        <span class="">Last updated: {{ getFormattedDate(project.updated_at) }}</span>
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
    <CreateProject :show-modal="showModal" :project-data="projectData" @close-modal="closeCreateModal" />
</template>