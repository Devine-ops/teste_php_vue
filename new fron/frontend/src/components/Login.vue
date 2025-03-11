<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div>
        <label>Email:</label>
        <input v-model="form.email" type="email" required />
      </div>
      <div>
        <label>Senha:</label>
        <input v-model="form.password" type="password" required />
      </div>
      <button type="submit">Entrar</button>
    </form>
  </div>
</template>

<script>
import axios from '@/axios'; // Caminho para o arquivo de configuração do Axios

export default {
  data() {
    return {
      form: {
        email: '',
        password: ''
      }
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('/api/login', this.form);
        localStorage.setItem('token', response.data.token); // Armazenando o token
        
        // Redireciona para a página de dashboard após login
        this.$router.push('/dashboard');
      } catch (error) {
        console.error('Erro ao fazer login:', error.response.data);
      }
    }
  }
};
</script>
