
import router from './js/routes/router.js';
import store from './js/store/store.js';
import { createApp } from 'vue';
import App from './App.vue';

createApp(App).use(store).use(router).mount('#app');
