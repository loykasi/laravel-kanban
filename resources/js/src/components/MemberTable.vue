<script setup lang="ts">
import { ref } from 'vue';
import { MemberType, GetMemberType } from '../pages/admin/member/action/getMember';
import utility from '../helper/utility';

defineProps<{
    members: GetMemberType;
    loading: boolean
}>()

const emit = defineEmits<{
    (e: 'editMember', member: MemberType): void,
    (e: 'getMember', page: number, query: string): Promise<void>
}>()

const query = ref('');
const search = utility.myDebounce(async function() {
    emit('getMember', 1, query.value);
}, 200);
</script>
<template>
    <div class="row">
        <div class="row">
            <div class="col-md-4">
                <input @keydown="search" v-model="query" type="text" placeholder="Search" class="form-control" />
                <span style="color:blue" v-show="loading"><b>Searching</b></span>
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="member in members?.data?.data" :key="member.id">
                        <td>{{ member.id }}</td>
                        <td>{{ member.name }}</td>
                        <td>{{ member.email }}</td>
                        <td>
                            <button @click="emit('editMember', member)" class="btn btn-outline-primary">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <slot name="pagination"></slot>
    </div>
</template>