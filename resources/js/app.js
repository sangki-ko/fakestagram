require('./bootstrap');

import { createApp } from 'vue';
import router from './router';
import AppComponent from '../views/components/AppComponent.vue';
import store from '../js/store/store';

createApp({
	components:{
		AppComponent,
	}
})
.use(store)
.use(router)
.mount('#app');