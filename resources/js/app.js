// resources/js/app.js
import { createApp } from 'vue';
import router from './router';
import App from './App.vue';

// Importar Bootstrap desde SCSS
import '../css/app.scss';

const app = createApp(App);
app.use(router);
app.mount('#app');