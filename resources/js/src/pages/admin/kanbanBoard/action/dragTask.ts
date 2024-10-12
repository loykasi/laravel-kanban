import { makeHttpRequest } from "../../../../helper/makeHttpRequest";
import toastNotification from "../../../../helper/toastNotification";
import utility from "../../../../helper/utility";

export function useDragTask() {
    let taskObject: HTMLElement;
    function startDrag(event: DragEvent, taskId: number) {
        if (event.dataTransfer !== null) {
            event.dataTransfer.dropEffect = 'move'
            event.dataTransfer.effectAllowed = 'move'
            event.dataTransfer.setData('text/plain', taskId.toString());
        }
        console.log(event);
        console.log(`taskId: ${taskId.toString()}`);
        taskObject = event.target as HTMLElement;
    }
    function onDrop(event: DragEvent, projectId: number) {
        event.preventDefault();
        const taskId = event.dataTransfer?.getData('text/plain');
        // console.log(`drop taskId: ${taskId}`);
        if (event.target instanceof HTMLElement && taskId !== undefined) {
            event.target.append(taskObject);
            let status: number = +event.target.id;
            setTaskStatus(+taskId, projectId, status);
        }
    }
    return { startDrag, onDrop }
}

export type changeTaskStatusInput = { 
    taskId: number; 
    projectId: number;
    status: number;
}

async function setTaskStatus(taskId: number, projectId: number, status: number) {
    try {
        console.log('make http request');
        const data = await makeHttpRequest<changeTaskStatusInput, { message: string }>(
                'task/set-status',
                'POST',
                {
                    taskId: taskId,
                    projectId: projectId,
                    status: status
                }
            );
        toastNotification.showSuccess(data.message);
    } catch (error) {
        utility.showErrorMessage(error);
    }
}