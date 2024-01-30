import { createRouter, createWebHistory } from 'vue-router';

import accommodations from '../components/pages/accommodations.vue';
import dashboard from '../components/pages/dashboard.vue';
import login from '../components/pages/login.vue';
import notFound from '../components/pages/notFound.vue';
import profile from '../components/pages/profile.vue';
import register from '../components/pages/register.vue';

const routes = [
    //login
    {
        path: '/login',
        component: login
    },
     //register
    {
        path: '/register',
        component: register
    },
     {
        path: '/dashboard',
        component: dashboard
    },
    {
        path: '/profile',
        component: profile
    },
    {
        path: '/accommodations',
        component: accommodations
    },
    //notfound
    {
        path: '/:pathMatch(.*)*',
        component: notFound
    }
]


const router = createRouter({
    history: createWebHistory(),
    routes
});


export default router;
