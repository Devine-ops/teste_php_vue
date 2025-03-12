// main.js
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';

// Cria uma instância do Vuetify
const vuetify = createVuetify({
    components,
    directives,
});

// Cria a aplicação Vue
const app = createApp(App);

// Usa os plugins
app.use(vuetify);
app.use(router);

// Monta a aplicação
app.mount('#app');
