import { defineStore } from 'pinia';
import { MemberInputType } from '../pages/admin/member/action/createMember';

const useMemberStore = defineStore('member', {
    state: () => ({
        memberInput: {} as MemberInputType,
        edit: false
    })
});

export const memberStore = useMemberStore();