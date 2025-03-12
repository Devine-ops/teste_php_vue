import { createRouter, createWebHistory } from 'vue-router';
import RegisterView from '../views/RegisterView.vue'; // A página de registro
import LoginView from '@/views/LoginView.vue';
import DashboardView from '@/views/DashboardView.vue';
import Home from '../components/Home.vue';

const routes = [

  {
    path: '/',
    name: 'home',
    component: Home,
  },

  {
    path: '/register',
    name: 'register',
    component: RegisterView
  },

  {
    path: '/login',
    name: 'login',
    component: LoginView
  },

  {
    path: '/dashboard',
    name: 'dashboard',
    component: DashboardView,
    // Adicionando uma guarda para verificar se o usuário está autenticado
    beforeEnter: (to, from, next) => {
      const token = localStorage.getItem("token");

      // Se o token não existir, redireciona para a página de login
      if (!token) {
        next({ name: 'login' });
      } else {
        next(); // Se o token existir, permite a navegação
      }
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
