<script setup lang="ts">
import { onMounted } from 'vue';
import MemberTable from '../../../components/MemberTable.vue';
import { MemberType, useGetMembers } from './action/getMember';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { useRouter } from 'vue-router';
import { memberStore } from '../../../store/memberStore';
import { MemberInputType } from './action/createMember';

const { getMembers, memberData, loading } = useGetMembers()

async function showListOfMembers() {
    await getMembers();
}

const router = useRouter();
function editMember(member: MemberType) {
    memberStore.memberInput = member;
    memberStore.edit = true;
    router.push('/create-member')
}

onMounted(async ()=>{
    showListOfMembers();
    memberStore.memberInput = {} as MemberInputType;
    memberStore.edit = false;
})
</script>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Member
                        <RouterLink style="float:right" to="/create-member" class="btn btn-primary">Create Member</RouterLink>
                    </div>
                    <div class="card-body">
                        <MemberTable @edit-member="editMember" :loading="loading" @get-member="getMembers" :members="memberData">
                            <template #pagination>
                                <Bootstrap5Pagination v-if="memberData?.data" :data="memberData?.data" @pagination-change-page="getMembers" />
                            </template>
                        </MemberTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>