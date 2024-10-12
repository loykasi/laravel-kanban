import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory('/app'),
    routes: [
        {
            path: '/register',
            name: 'register',
            component:() => import('../pages/auth/AuthPage.vue'),
            children: [
                {
                    path: '/register',
                    name: 'register',
                    component:() => import('../pages/auth/RegisterPage.vue')
                },
                {
                    path: '/login',
                    name: 'login',
                    component:() => import('../pages/auth/LoginPage.vue')
                }
            ]
        },
        {
            path: '/admin',
            name: 'admin',
            component:() => import('../pages/admin/AdminPage.vue'),
            children: [
                {
                    path: '/admin',
                    name: 'admin',
                    component:() => import('../pages/admin/dashboard/DashboardPage.vue')
                },
                {
                    path: '/member',
                    name: 'member',
                    component:() => import('../pages/admin/member/MemberPage.vue')
                },
                {
                    path: '/create-member',
                    name: 'create-member',
                    component:() => import('../pages/admin/member/CreateMember.vue')
                },
                {
                    path: '/project',
                    name: 'project',
                    component:() => import('../pages/admin/project/ProjectPage.vue')
                },
                {
                    path: '/create-project',
                    name: 'create-project',
                    component:() => import('../pages/admin/project/CreateProject.vue')
                },
                {
                    path: '/kaban',
                    name: 'kaban',
                    component:() => import('../pages/admin/kanbanBoard/KanbanBoard.vue')
                }
            ]
        }
    ]
})

export default router