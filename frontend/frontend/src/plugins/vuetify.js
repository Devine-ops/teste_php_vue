import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import { aliases, mdi } from 'vuetify/iconsets/mdi';

import {
    VBtn,
    VRow,
    VCard,
    VCardTitle,
    VCardText,
    VCardActions,
    VTextField,
    VCol,
    VSelect,
    VTextarea,
    VExpandTransition,
    VForm,
    VAlert,
    VContainer,
    VDataTable,
    VIcon
} from 'vuetify/components';

// Criando o Vuetify com temas personalizados
const vuetify = createVuetify({
    components: {
        VBtn,
        VRow,
        VCard,
        VCardTitle,
        VCardText,
        VCardActions,
        VTextField,
        VCol,
        VSelect,
        VTextarea,
        VExpandTransition,
        VForm,
        VAlert,
        VContainer,
        VDataTable,
        VIcon
    },
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
    theme: {
        defaultTheme: 'light', // Definindo o tema padrão
        themes: {
            light: {
                // Personalize o tema claro
                primary: '#6200ea',
                secondary: '#03dac6',
                accent: '#ff4081',
                background: '#f5f5f5',
                surface: '#ffffff',
                error: '#b00020',
                info: '#2196f3',
                success: '#4caf50',
                warning: '#fb8c00',
            },
            dark: {
                // Personalize o tema escuro
                primary: '#bb86fc',
                secondary: '#03dac6',
                accent: '#03dac6',
                background: '#121212',
                surface: '#1e1e1e',
                error: '#cf6679',
                info: '#2196f3',
                success: '#4caf50',
                warning: '#fb8c00',
            },
        },
    },
});

export default vuetify;
