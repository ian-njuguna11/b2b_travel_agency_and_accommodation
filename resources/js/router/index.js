import { createRouter, createWebHistory } from 'vue-router';
import { createStore } from 'vuex';

import accommodations from '../components/pages/accommodations.vue';
import contracts from '../components/pages/contracts.vue';
import dashboard from '../components/pages/dashboard.vue';
import login from '../components/pages/login.vue';
import notFound from '../components/pages/notFound.vue';
import profile from '../components/pages/profile.vue';
import register from '../components/pages/register.vue';



const store = createStore();

const routes = [
    {
        path: '/',
        component: login,
        meta: {
            requiresAuth: false
        }
    },
    //login
    {
        path: '/login',
        name: 'Login',
        component: login,
        meta: {
            requiresAuth: false
        }
    },
     //register
    {
        path: '/register',
        name: 'Register',
        component: register,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: dashboard,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/profile',
        name: 'Profile',
        component: profile,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/accommodations',
        name: 'Accommodations',
        component: accommodations,
        meta: {
            requiresAuth: true
        }
    },
     {
        path: '/contracts',
        name: 'Contracts',
        component: contracts,
        meta: {
            requiresAuth: true
        }
    },
    //notfound
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: notFound,
        meta: {
            requiresAuth: true
        }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
});


router.beforeEach((to) => {
    // to and from are both route objects. must call `next`.

    if (to.meta.requiresAuth &&  !localStorage.getItem('token')) {
        return { name: 'Login' }
    }

    if (to.meta.requiresAuth == false && localStorage.getItem('token')) {
        return { name: 'Dashboard' }
    }

})


export default router;
