import { createRouter, createWebHistory } from 'vue-router';

// Define your routes
const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../Pages/Home.vue'), // Lazy-loaded
    },
    {
        path: '/about',
        name: 'About',
        component: () => import('../components/About.vue'), // Lazy-loaded
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../components/Auth/Registration.vue'), // Lazy-loaded
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../components/Auth/LoginForm.vue'), // Lazy-loaded
    },
];

// Create the router instance
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
