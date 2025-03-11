<template>
  <div class="container">
    <h2>Criação de Conta</h2>

    <form @submit.prevent="register">
      <div>
        <label>Nome:</label>
        <input v-model="form.name" type="text" required />
      </div>

      <div>
        <label>Email:</label>
        <input v-model="form.email" type="email" required />
      </div>

      <div>
        <label>Senha:</label>
        <input v-model="form.password" type="password" required />
      </div>

      <button type="submit">Criar Conta</button>
    </form>

    <p v-if="message">{{ message }}</p>
    <ul v-if="errors">
      <li v-for="(error, field) in errors" :key="field">
        {{ error[0] }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router"; // Importando o useRouter

const router = useRouter(); // Usando o router

const form = ref({
  name: "",
  email: "",
  password: ""
});

const message = ref("");
const errors = ref(null);

const register = async () => {
  message.value = "";
  errors.value = null;

  try {
    const response = await axios.post("http://127.0.0.1:8000/api/register", form.value);
    message.value = response.data.message;
    
    // Após o sucesso no registro, redireciona para a página de login
    setTimeout(() => {
      router.push("/login");
    }, 3000);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data;
    } else {
      message.value = "Erro ao criar conta.";
    }
  }
};
</script>

<style scoped>
.container {
  max-width: 400px;
  margin: auto;
  padding: 20px;
}
form div {
  margin-bottom: 10px;
}
button {
  background: #42b983;
  color: white;
  padding: 10px;
  border: none;
  cursor: pointer;
}
</style>
