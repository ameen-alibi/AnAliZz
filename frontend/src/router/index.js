import { createRouter, createWebHistory } from 'vue-router';
import LoginForm from '../components/auth/LoginForm.vue';
import RegisterForm from '../components/auth/RegisterForm.vue';
import WebsitesList from '../components/sites/WebsitesList.vue';
import AddWebsiteForm from '../components/sites/AddWebsiteForm.vue';
import WebsiteStats from '../components/sites/WebsiteStats.vue';
import { useAuthStore } from '../store/auth';

const routes = [
    { path: '/login', component: LoginForm },
    { path: '/register', component: RegisterForm },
    { path: '/sites', component: WebsitesList, meta: { auth: true } },
    { path: '/sites/new', component: AddWebsiteForm, meta: { auth: true } },
    { path: '/sites/:id', component: WebsiteStats, meta: { auth: true } },
    { path: '/:pathMatch(.*)*', redirect: '/login' }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});


router.beforeEach((to, from, next) => {
    const auth = useAuthStore();
    if (to.meta.auth && !auth.isLoggedIn) {
        return next('/login');
    }
    next();
});

export default router;
