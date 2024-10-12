import { defineStore } from 'pinia';
import { CreateTaskInput } from '../pages/admin/kanbanBoard/action/createTask';

const useTaskStore = defineStore('task', {
    state: () => ({
        taskInput: {} as CreateTaskInput,
        edit: false
    })
});

export const taskStore = useTaskStore();