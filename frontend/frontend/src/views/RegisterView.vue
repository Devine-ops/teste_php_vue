<template>
  <!-- Barra de navegação -->
  <v-app-bar app>
    <v-toolbar-title>MONEY FLOW</v-toolbar-title>
    <v-spacer></v-spacer>

    <!-- Botões de Navegação -->
    <v-btn to="/">Ir para Home</v-btn>
    <v-btn text to="/login">Login</v-btn>
    <v-btn text to="/register">Criar Conta</v-btn>
  </v-app-bar>
  <v-container class="register-container">
    <v-row justify="center" align="center" class="fill-height">
      <v-col cols="12" md="4">
        <v-card class="pa-5" elevation="2">
          <v-card-title>
            <h2 class="text-center">Criar Conta</h2>
          </v-card-title>

          <v-form @submit.prevent="register">
            <!-- Nome -->
            <v-text-field
              v-model="form.name"
              label="Nome"
              required
              outlined
            ></v-text-field>

            <!-- Email -->
            <v-text-field
              v-model="form.email"
              label="Email"
              type="email"
              required
              outlined
            ></v-text-field>

            <!-- Senha -->
            <v-text-field
              v-model="form.password"
              label="Senha"
              type="password"
              required
              outlined
            ></v-text-field>

            <!-- Botão de criação de conta -->
            <v-btn
              type="submit"
              color="primary"
              class="w-100"
              :loading="loading"
              :disabled="loading"
            >
              Criar Conta
            </v-btn>

            <!-- Mensagem de sucesso ou erro -->
            <v-alert v-if="message" type="success" class="mt-3" dense>{{
              message
            }}</v-alert>

            <!-- Exibição de erros de validação -->
            <v-alert v-if="errors" type="error" class="mt-3" dense>
              <ul>
                <li v-for="(error, field) in errors" :key="field">
                  {{ error[0] }}
                </li>
              </ul>
            </v-alert>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const form = ref({
  name: "",
  email: "",
  password: "",
});

const message = ref("");
const errors = ref(null);
const loading = ref(false);

const register = async () => {
  message.value = "";
  errors.value = null;
  loading.value = true;

  try {
    const response = await axios.post(
      "http://127.0.0.1:8000/api/register",
      form.value
    );
    message.value = response.data.message;

    // Redireciona para a página de login após 3 segundos
    setTimeout(() => {
      router.push("/login");
    }, 3000);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      message.value = "Erro ao criar conta.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.register-container {
  min-height: 100vh;
}

.register-card {
  max-width: 400px;
  margin: 0 auto;
}
</style>
