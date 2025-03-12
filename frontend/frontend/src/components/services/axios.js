// src/axios.js ou src/services/axios.js
import axios from 'axios';

// Configuração para enviar cookies de sessão, caso esteja usando Laravel Sanctum
axios.defaults.withCredentials = true;

// URL base da API Laravel (ajuste conforme necessário)
axios.defaults.baseURL = 'http://127.0.0.1:8000'; // Substitua pela URL do seu backend

export default axios;
