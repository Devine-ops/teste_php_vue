<template>
  <div class="dashboard">
    <h2>Dashboard</h2>

    <!-- Botão de logout -->
    <button @click="logout">Logout</button>

    <!-- Botão para carregar as movimentações -->
    <button @click="fetchMovimentacoes">Carregar Movimentações</button>

    <!-- Tabela com as movimentações -->
    <table v-if="movimentacoes.length > 0">
      <thead>
        <tr>
          <th>Data</th>
          <th>Tipo</th>
          <th>Valor</th>
          <th>Categoria</th>
          <th>Descrição</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="movimentacao in movimentacoes" :key="movimentacao.id">
          <td>{{ movimentacao.date }}</td>
          <td>{{ movimentacao.type }}</td>
          <td>{{ movimentacao.value }}</td>
          <td>{{ movimentacao.category }}</td>
          <td>{{ movimentacao.description }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Mensagem caso não haja movimentações -->
    <p v-if="movimentacoes.length === 0">Nenhuma movimentação encontrada.</p>

    <!-- Erro de autenticação -->
    <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
  </div>
</template>

<script>
import axios from "axios"; // Certifique-se de que axios está configurado corretamente
import { useRouter } from "vue-router"; // Para redirecionamento após o logout

export default {
  data() {
    return {
      movimentacoes: [], // Lista de movimentações
      errorMessage: "", // Mensagem de erro
    };
  },
  methods: {
    // Método para carregar as movimentações
    async fetchMovimentacoes() {
      // Recuperando o token do localStorage
      const token = localStorage.getItem("token");

      if (!token) {
        this.errorMessage = "Token de autenticação não encontrado!";
        return;
      }

      try {
        // Enviando a requisição com o token de autenticação
        const response = await axios.get("http://127.0.0.1:8000/api/movimentacoes", {
          headers: {
            Authorization: `Bearer ${token}`, // Enviando o token no cabeçalho
          },
        });

        // Armazenando as movimentações no estado
        this.movimentacoes = response.data;

        // Limpar mensagem de erro, caso haja
        this.errorMessage = "";
      } catch (error) {
        // Exibindo mensagem de erro caso a requisição falhe
        if (error.response && error.response.status === 401) {
          this.errorMessage = "Token de autenticação inválido!";
        } else {
          this.errorMessage = "Erro ao carregar as movimentações.";
        }
      }
    },
    // Método para logout
    logout() {
      // Remover o token de autenticação do localStorage
      localStorage.removeItem("token");

      // Redirecionar para a página de login
      this.$router.push("/login");
    },
  },
};
</script>

<style scoped>
.dashboard {
  padding: 20px;
}

button {
  background-color: #42b983;
  color: white;
  padding: 10px;
  border: none;
  cursor: pointer;
  margin-bottom: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

table th,
table td {
  padding: 8px;
  text-align: left;
  border: 1px solid #ddd;
}

.error {
  color: red;
  font-size: 14px;
  margin-top: 20px;
}
</style>
