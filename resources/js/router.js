import {createWebHistory, createRouter } from 'vue-router';
import LoginComponent from '../views/components/auth/LoginComponent.vue';
import BoardListComponent from '../views/components/board/BoardListComponent.vue';
import NotFoundComponent from '../views/components/NotFoundComponent.vue';
import BoardCreateComponent from '../views/components/board/BoardCreateComponent.vue';
import { useStore } from 'vuex';

const chkAuth = (to, from, next) => {
	const store = useStore();
	const authFlg = store.state.user.authFlg;
	const noAuthPassFlg = (to.path === '/' || to.path === '/login' || to.path === 'registration');

	if(authFlg && noAuthPassFlg) {
		next('/boards')
	}else if(!authFlg && !noAuthPassFlg) {
		next('/login');
	}else {
		next();
	}
}

const routes=[
	{
		path: '/'
		,component: LoginComponent,
		beforeEnter: chkAuth,
	},
	{
		path: '/login'
		,component: LoginComponent,
		beforeEnter: chkAuth,
	},
	{
		path: '/boards'
		,component: BoardListComponent,
		beforeEnter: chkAuth,
	},
	{
        path: '/:catchAll(.*)',
        component: NotFoundComponent,
    },
	{
		path: '/boards/create'
		,component: BoardCreateComponent,
	}
];

const router = createRouter({
	history: createWebHistory()
	,routes
});

export default router;