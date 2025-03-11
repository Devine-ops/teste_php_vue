import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Importe o Vue Router

createApp(App)
  .use(router) // Use o Vue Router
  .mount('#app');
