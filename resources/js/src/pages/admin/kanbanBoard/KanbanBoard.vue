<script setup lang="ts">
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import BreadCrumb from '../../../components/BreadCrumb.vue'
import { useGetProjectDetail } from './action/getProjectDetail';
import AddTaskModal from './AddTaskModal.vue';
import utility from '../../../helper/utility';
import { MemberType, useGetMembers } from '../member/action/getMember';
import { taskStore } from '../../../store/kanbanStore';
import { TaskStatus } from './action/getProjectDetail.types';
import { useDragTask } from './action/dragTask';


const route = useRoute();
const { getProjectDetail, projectDetail } = useGetProjectDetail();
const { getMembers, memberData, loading } = useGetMembers();
const { startDrag, onDrop } = useDragTask();

const query = route.query?.query as string;

async function openModal() {
    utility.openModal('taskModal').then(()=>{
        taskStore.taskInput.projectId = projectDetail.value?.data.id;
        taskStore.taskInput.memberIds = [];
        console.log('open modal');
    })
}

async function closeModal() {
    utility.closeModal('taskModal');
}

function onTestDrop() {
    console.log('drop');
}

onMounted(async ()=>{
    await getProjectDetail(query);
    getMembers(1, '');
})
</script>
<template>
    <ul class="flex h-full">
        <li
            @drop="onTestDrop()"
            @dragenter.prevent
            @dragover.prevent
            class="p-2"
        >
            <div
                draggable="true"
                class="w-72 bg-gray-50 border rounded-xl shadow px-2 pb-2"
            >
                <div class="font-semibold text-sm px-3 py-3">Game Features (May Have)</div>
                <div>
                    <div>
                        <div draggable="true" class="bg-white border border-slate-200 rounded-xl shadow px-4 py-2 mb-2">Card 1</div>
                    </div>
                    <div>
                        <div draggable="true" class="bg-white border border-slate-200 rounded-xl shadow px-4 py-2 mb-2">Card 1</div>
                    </div>
                    <div>
                        <div draggable="true" class="bg-white border border-slate-200 rounded-xl shadow px-4 py-2 mb-2">Unlock things by completing achievements</div>
                    </div>
                </div>
                <div class="flex items-center font-semibold text-sm text-slate-700 px-3 py-1 rounded-xl hover:bg-gray-200">
                    <svg class="w-6 h-6 mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                    </svg>
                    <span>Add a card</span>
                </div>
            </div>
        </li>
        <li
            @drop="onTestDrop()"
            @dragenter.prevent
            @dragover.prevent
            class="p-2"
        >
            <div
                draggable="true"
                class="w-72 bg-gray-50 border rounded-xl shadow px-2 pb-2"
            >
                <div class="font-semibold text-sm px-3 py-3">TO DO LIST</div>
                <div class="flex items-center font-semibold text-sm text-slate-700 px-2 py-1 rounded-xl hover:bg-gray-200">
                    <svg class="w-6 h-6 mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                    </svg>
                    <span>Add a card</span>
                </div>
            </div>
        </li>
        <div class="p-2">
            <div class="w-72 bg-opacity-50 border rounded-xl shadow px-2 py-3 flex items-center font-semibold text-sm text-slate-700 hover:bg-gray-200">
                <svg class="w-6 h-6 mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                </svg>
                <span>Add new list</span>
            </div>
        </div>
    </ul>

    <!-- <div class="row">
        <AddTaskModal @get-members="getMembers" @close-modal="closeModal" :members="memberData" />
        <BreadCrumb />
        <div class="row" style="margin-bottom: 20px">
            <h3 style="color: #767778">
                Project: {{ projectDetail?.data?.name }}
            </h3>
            <span>
                StartDate : <b> {{ projectDetail?.data?.startDate }} </b>
                EndDate : <b>{{ projectDetail?.data?.endDate }}</b>
            </span>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div
                    class="progress"
                    role="progressbar"
                    aria-label="Example with label"
                    aria-valuenow="5"
                    aria-valuemin="0"
                    aria-valuemax="100"
                >
                    <div
                        class="progress-bar bg-success"
                        :style="{width:parseInt(projectDetail?.data?.task_progress?.progress)+'%'}"
                    >
                        {{ projectDetail?.data?.task_progress?.progress }}
                        %
                    </div>
                </div>
            </div>
        </div>

        <br />

        <div class="card">
            <div class="card-body">
                <div class="row" style="height: 500px;">
                    <div 
                        @drop="onDrop($event, projectDetail.data.id)"
                        @dragenter.prevent
                        @dragover.prevent
                        class="col-md-4 bg-info not_started_task"
                        :id="TaskStatus.NOT_STARTED.toString()"
                    >
                        <div class="card card-header">
                            <div @click="openModal" class="btn btn-warning">Add Task</div>
                        </div>
                        <div 
                            v-for="task in projectDetail?.data?.tasks" :key="task.id"
                            v-show="task.status === TaskStatus.NOT_STARTED"
                            draggable="true"
                            @dragstart="startDrag($event, task.id)"
                            class="card card-body mt-2">
                            <p>{{ task.name }}</p>
                            <div v-if="task.task_members.length > 0" class="assignees">
                                <button 
                                    v-for="member in task.task_members" :key="member.id"
                                    class="member_1 btn btn-primary rounded-circle border"
                                >
                                    {{ utility.getChar(member?.member.name) }}
                                </button>
                                {{ task?.task_members?.length }} assignees
                            </div>
                        </div>
                    </div>
                    <div
                        @drop="onDrop($event, projectDetail.data.id)"
                        @dragenter.prevent
                        @dragover.prevent
                        class="col-md-4 bg-light pending_task"
                        :id="TaskStatus.PENDING.toString()"
                    >
                        <div class="card card-header">
                            <b>Pending</b>
                        </div>
                        <div 
                            v-for="task in projectDetail?.data?.tasks" :key="task.id"
                            v-show="task.status === TaskStatus.PENDING"
                            draggable="true"
                            @dragstart="startDrag($event, task.id)"
                            class="card card-body mt-2">
                            <p>{{ task.name }}</p>
                            <div v-if="task.task_members.length > 0" class="assignees">
                                <button 
                                    v-for="member in task.task_members" :key="member.id"
                                    class="member_1 btn btn-primary rounded-circle border"
                                >
                                    {{ utility.getChar(member?.member.name) }}
                                </button>
                                {{ task?.task_members?.length }} assignees
                            </div>
                        </div>
                    </div>
                    <div
                        @drop="onDrop($event, projectDetail.data.id)"
                        @dragenter.prevent
                        @dragover.prevent
                        class="col-md-4 completed_task"
                        :id="TaskStatus.COMPLETED.toString()"
                    >
                        <div class="card card-header">
                            <b>Completed</b>
                        </div>
                        <div 
                            v-for="task in projectDetail?.data?.tasks" :key="task.id"
                            v-show="task.status === TaskStatus.COMPLETED"
                            draggable="true"
                            @dragstart="startDrag($event, task.id)"
                            class="card card-body mt-2">
                            <p>{{ task.name }}</p>
                            <div v-if="task.task_members.length > 0" class="assignees">
                                <button 
                                    v-for="member in task.task_members" :key="member.id"
                                    class="member_1 btn btn-primary rounded-circle border"
                                >
                                    {{ utility.getChar(member?.member.name) }}
                                </button>
                                {{ task?.task_members?.length }} assignees
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</template>

<style>
    .hovered {
        border: 2px dashed rgb(122, 122, 122);
        border-radius: 5px;
    }
</style>