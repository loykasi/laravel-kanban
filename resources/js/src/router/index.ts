import { createRouter, createWebHashHistory, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component:() => import('../pages/home/HomePage.vue'),
        },
        {
            path: '/verify-email/:token',
            name: 'verify-email',
            component:() => import('../pages/auth/VerifyEmailPage.vue'),
        },
        {
            path: '/u',
            name: 'u',
            redirect: '/register',
            component:() => import('../pages/auth/AuthPage.vue'),
            children: [
                {
                    path: '/register',
                    name: 'register',
                    component:() => import('../pages/auth/RegisterPage.vue')
                },
                {
                    path: '/verify-email',
                    name: 'inform-verify-email',
                    component:() => import('../pages/auth/InformEmailVerificationPage.vue')
                },
                {
                    path: '/login',
                    name: 'login',
                    component:() => import('../pages/auth/LoginPage.vue')
                }
            ]
        },
        {
            path: '/project',
            name: 'project',
            component:() => import('../pages/admin/AdminPage.vue'),
            children: [
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
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'notfound',
            component:() => import('../pages/home/NotFoundPage.vue')
        }
    ]
})

export default router