<template>
  <v-app>
    <!-- Barra de navegação -->
    <v-app-bar app>
      
      <v-toolbar-title>MONEY FLOW</v-toolbar-title>
      <v-spacer></v-spacer>

      <!-- Botões de Navegação -->
      <v-btn to="/">Ir para Home</v-btn>
      <v-btn text to="/login">Login</v-btn>
      <v-btn text to="/register">Criar Conta</v-btn>
    </v-app-bar>

    <!-- Conteúdo principal -->
    <v-main>
      <v-container class="login-container">
        <v-row justify="center" align="center" class="fill-height">
          <v-col cols="12" md="4">
            <v-card class="pa-5" elevation="2">
              <v-card-title>
                <h2 class="text-center">Login</h2>
              </v-card-title>

              <v-form @submit.prevent="handleLogin">
                <v-text-field
                  v-model="email"
                  label="Email"
                  type="email"
                  required
                  outlined
                ></v-text-field>

                <v-text-field
                  v-model="password"
                  label="Senha"
                  type="password"
                  required
                  outlined
                ></v-text-field>

                <v-btn
                  type="submit"
                  color="primary"
                  class="w-100"
                  :loading="loading"
                  :disabled="loading"
                >
                  {{ loading ? "Entrando..." : "Entrar" }}
                </v-btn>

                <v-alert
                  v-if="errorMessage"
                  type="error"
                  class="mt-3"
                  dense
                >{{ errorMessage }}</v-alert>
              </v-form>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

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