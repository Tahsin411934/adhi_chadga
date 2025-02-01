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
    {
        path: '/order/confirm',
        name: 'ConfirmOrder',
        component: () => import('../Pages/ConfirmOrder.vue'), // Lazy-loaded
    },
    {
        path: '/dashboard',
        name: 'Layout',
        component: () => import('../Pages/Dashboard/Layout.vue'),  // The parent component for the dashboard
        children: [
          {
            path: '/dashboard',  // Relative path for the login route under the dashboard
            name: 'Dashoboard',
            component: () => import('../Pages/Dashboard/Dashboard.vue'),
          },
          {
            path: '/dashboard/catagories',  // Relative path for the login route under the dashboard
            name: 'Catagories',
            component: () => import('../Pages/Dashboard/Catagory.vue'),
          },
          {
            path: '/dashboard/items',  // Relative path for the login route under the dashboard
            name: 'Item',
            component: () => import('../Pages/Dashboard/FoodItem.vue'),
          },
          {
            path: '/dashboard/pandingOrder',  // Relative path for the login route under the dashboard
            name: 'PendingOrder',
            component: () => import('../Pages/Dashboard/Order/PandingOrder.vue'),
          },
          {
            path: '/dashboard/completeOrder',  // Relative path for the login route under the dashboard
            name: 'completeOrder',
            component: () => import('../Pages/Dashboard/Order/CompleteOrder.vue'),
          },
        ]
      }
      
];

// Create the router instance
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
