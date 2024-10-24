import { createRouter, createWebHistory } from 'vue-router';
import Home from "../components/Home.vue";
import Subscription from '../components/Subscription.vue';

const routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/subscriptions',
        component: Subscription,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
