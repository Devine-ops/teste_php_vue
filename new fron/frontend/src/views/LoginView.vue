<template>
  <div class="login-container">
    <div class="login-card">
      <h2>Login</h2>

      <form @submit.prevent="handleLogin">
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="email" required />
        </div>

        <div class="input-group">
          <label for="password">Senha</label>
          <input type="password" id="password" v-model="password" required />
        </div>

        <button type="submit" class="login-btn" :disabled="loading">
          {{ loading ? "Entrando..." : "Entrar" }}
        </button>

        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const email = ref("");
const password = ref("");
const errorMessage = ref("");
const loading = ref(false);
const router = useRouter();

const handleLogin = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.post("http://127.0.0.1:8000/api/login", {
      email: email.value,
      password: password.value,
    });

    if (response.data.token) {
      localStorage.setItem("token", response.data.token);
      router.push("/dashboard");
    } else {
      errorMessage.value = "Credenciais inválidas!";
    }
  } catch (error) {
    errorMessage.value = "Erro ao tentar fazer login. Verifique suas credenciais.";
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: #f5f5f5;
}

.login-card {
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 300px;
}

h2 {
  margin-bottom: 20px;
}

.input-group {
  text-align: left;
  margin-bottom: 15px;
}

.input-group label {
  display: block;
  font-size: 14px;
  margin-bottom: 5px;
}

.input-group input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.login-btn {
  width: 100%;
  padding: 10px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.login-btn:disabled {
  background-color: #999;
  cursor: not-allowed;
}

.error-message {
  color: red;
  font-size: 14px;
  margin-top: 10px;
}
</style>
