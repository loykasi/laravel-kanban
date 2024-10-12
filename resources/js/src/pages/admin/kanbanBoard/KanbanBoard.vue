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

onMounted(async ()=>{
    await getProjectDetail(query);
    getMembers(1, '');
})
</script>
<template>
    <div class="row">
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
    </div>
</template>

<style>
    .hovered {
        border: 2px dashed rgb(122, 122, 122);
        border-radius: 5px;
    }
</style>