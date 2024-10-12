import { ref } from "vue";
import { MemberType } from "../../member/action/getMember";
import utility from "../../../../helper/utility";
import { taskStore } from "../../../../store/kanbanStore";
import toastNotification from "../../../../helper/toastNotification";

export function useSelectMember() {
    const selectedMembers = ref<Array<MemberType>>([])

    function selectMember(member: MemberType) {
        const exist = selectedMembers.value.filter(memberItem => memberItem.id === member.id);

        if (exist.length == 0) {
            selectedMembers.value.push({
                id: member.id,
                name: member.name,
                email: member.email
            });
            taskStore.taskInput.memberIds.push(member.id);
        } else {
            toastNotification.showError('Already selected this member');
        }
    }

    function unselectMember(memberId: number) {
        const filteredMembers = selectedMembers.value.filter(memberItem => memberItem.id !== memberId);
        selectedMembers.value = filteredMembers;

        const filteredMemberIds = taskStore.taskInput.memberIds.filter(id => id !== memberId);
        taskStore.taskInput.memberIds = filteredMemberIds;
    }

    return { selectedMembers, selectMember, unselectMember };
}