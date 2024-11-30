import {createWebHistory, createRouter } from 'vue-router';
import LoginComponent from '../views/components/auth/LoginComponent.vue';

const routes=[
	{
		path : '/login'
		,component : LoginComponent
	}
];

const router = createRouter({
	history: createWebHistory()
	,routes
});

export default router;