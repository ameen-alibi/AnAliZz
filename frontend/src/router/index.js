import { createRouter, createWebHistory } from "vue-router"

import LoginForm from '@/components/auth/LoginForm.vue'
import RegisterForm from '@/components/auth/RegisterForm.vue'
import Dashboard from '@/components/Dashboard.vue'

const routes = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        component: LoginForm,
        name: 'Login'
    },
    {
        path: '/register',
        component: RegisterForm,
        name: 'Register'
    },
    {
        path: '/dashboard',
        component: Dashboard,
        name: 'Dashboard'
    },
    {
        path: '/sites',
        name: 'SitesList',
        component: () => import('@/pages/sites/SitesList.vue')
    },
    {
        path: '/sites/new',
        name: 'SiteCreate',
        component: () => import('@/pages/sites/SiteCreate.vue')
    },
    {
        path: '/sites/:id',
        name: 'SiteShow',
        component: () => import('@/pages/sites/SiteShow.vue'),
        props: true
    },
    {
        path: '/sites/:id/stats',
        name: 'SiteStats',
        component: () => import('@/pages/sites/SiteStats.vue'),
        props: true
    }

]

const router = createRouter({
    history: createWebHistory(),
    routes
})
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token')
    const publicRoutes = ['Login', 'Register']

    if (!publicRoutes.includes(to.name) && !isAuthenticated) {
        return next({ name: 'Login' })
    }

    if (publicRoutes.includes(to.name) && isAuthenticated) {
        return next({ name: 'Dashboard' })
    }

    next()
})

export default router 