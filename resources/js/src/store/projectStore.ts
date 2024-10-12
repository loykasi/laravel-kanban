import { defineStore } from 'pinia';
import { ProjectInputType } from '../pages/admin/project/action/createProject';

const useProjectStore = defineStore('project', {
    state: () => ({
        projectInput: {} as ProjectInputType,
        edit: false
    })
});

export const projectStore = useProjectStore();