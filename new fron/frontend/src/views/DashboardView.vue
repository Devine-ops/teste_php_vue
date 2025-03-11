<template>
  <div class="dashboard">
    <div class="logout">
      <button @click="logout" class="logout-btn">Logout</button>
    </div>

    <h2>Dashboard de Movimentações</h2>

    <!-- Formulário para adicionar movimentação -->
    <form @submit.prevent="addMovimentacao" class="form-container">
      <h3>{{ editMode ? 'Editar Movimentação' : 'Adicionar Movimentação' }}</h3>
      <div class="form-group">
        <label for="date">Data:</label>
        <input type="date" v-model="novaMovimentacao.date" required />
      </div>
      
      <div class="form-group">
        <label for="type">Tipo:</label>
        <select v-model="novaMovimentacao.type" required>
          <option value="entrada">Entrada</option>
          <option value="saida">Saída</option>
        </select>
      </div>
      
      <div class="form-group">
        <label for="value">Valor:</label>
        <input type="number" v-model="novaMovimentacao.value" step="0.01" required />
      </div>

      <div class="form-group">
        <label for="category">Categoria:</label>
        <!-- Dropdown de Categorias -->
        <select v-model="novaMovimentacao.category" required>
          <option value="" disabled>Selecione uma Categoria</option>
          <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.nome">
            {{ categoria.nome }}
          </option>
        </select>
        <!-- Botão para criar uma nova categoria -->
        <button type="button" @click="toggleCreateCategory" class="btn-create-category">
          Criar Nova Categoria
        </button>
      </div>

      <div class="form-group" v-if="isCreatingCategory">
        <label for="newCategory">Nome da Nova Categoria:</label>
        <input type="text" v-model="novaCategoria" placeholder="Digite o nome da categoria" />
        <button type="button" @click="createCategory" class="btn-submit">Criar Categoria</button>
      </div>

      <div class="form-group">
        <label for="description">Descrição:</label>
        <textarea v-model="novaMovimentacao.description" required></textarea>
      </div>

      <button type="submit" class="btn-submit">
        {{ editMode ? 'Atualizar Movimentação' : 'Adicionar Movimentação' }}
      </button>
    </form>

    <!-- Tabela com os dados -->
    <table v-if="movimentacoes.length > 0" class="movimentacao-table">
      <thead>
        <tr>
          <th>Data</th>
          <th>Tipo</th>
          <th>Valor</th>
          <th>Categoria</th>
          <th>Descrição</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="mov in movimentacoes" :key="mov.id">
          <td>{{ mov.date }}</td>
          <td>{{ mov.type }}</td>
          <td>R$ {{ Number(mov.value).toFixed(2) }}</td>
          <td>{{ mov.category }}</td>
          <td>{{ mov.description }}</td>
          <td>
            <button @click="editMovimentacao(mov)" class="btn-edit">Editar</button>
            <button @click="deleteMovimentacao(mov.id)" class="btn-delete">Deletar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Mensagens de erro ou carregamento -->
    <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    <p v-if="movimentacoes.length === 0 && !errorMessage">Nenhuma movimentação encontrada.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const movimentacoes = ref([]);
const categorias = ref([]);
const novaMovimentacao = ref({
  date: "",
  type: "",
  value: 0,
  category: "",
  description: ""
});
const novaCategoria = ref(""); // Variável para nova categoria
const isCreatingCategory = ref(false); // Controle para mostrar o formulário de criação de categoria
const errorMessage = ref("");

// Função para buscar categorias
const fetchCategorias = async () => {
  const token = localStorage.getItem("token");

  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }

  try {
    const response = await axios.get("http://127.0.0.1:8000/api/categorias", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    categorias.value = response.data;
  } catch (error) {
    errorMessage.value = "Erro ao carregar categorias.";
  }
};

// Função para buscar movimentações
const fetchMovimentacoes = async () => {
  const token = localStorage.getItem("token");

  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }

  try {
    const response = await axios.get("http://127.0.0.1:8000/api/movimentacoes", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    movimentacoes.value = response.data;
  } catch (error) {
    errorMessage.value = "Erro ao carregar movimentações.";
  }
};

// Função para adicionar ou editar movimentação
const addMovimentacao = async () => {
  const token = localStorage.getItem("token");

  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }

  try {
    if (editMode.value) {
      const response = await axios.put(
        `http://127.0.0.1:8000/api/movimentacoes/${novaMovimentacao.value.id}`,
        novaMovimentacao.value,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      editMode.value = false;
      currentMovimentacao.value = null;
    } else {
      const response = await axios.post(
        "http://127.0.0.1:8000/api/movimentacoes",
        novaMovimentacao.value,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
    }

    novaMovimentacao.value = {
      date: "",
      type: "",
      value: 0,
      category: "",
      description: "",
    };

    fetchMovimentacoes();
    errorMessage.value = "";
  } catch (error) {
    errorMessage.value = "Erro ao adicionar ou editar movimentação.";
  }
};

// Função para editar movimentação
const editMode = ref(false);
const currentMovimentacao = ref(null);

const editMovimentacao = (mov) => {
  editMode.value = true;
  currentMovimentacao.value = mov;
  novaMovimentacao.value = { ...mov };  // Preenche o formulário com a movimentação selecionada
};

// Função para deletar movimentação
const deleteMovimentacao = async (id) => {
  const token = localStorage.getItem("token");

  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }

  try {
    await axios.delete(`http://127.0.0.1:8000/api/movimentacoes/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    fetchMovimentacoes();
    errorMessage.value = "";
  } catch (error) {
    errorMessage.value = "Erro ao deletar movimentação.";
  }
};

// Função para criar categoria
const createCategory = async () => {
  const token = localStorage.getItem("token");

  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }

  try {
    await axios.post(
      "http://127.0.0.1:8000/api/categorias",
      { nome: novaCategoria.value },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );
    novaCategoria.value = "";  // Limpa o campo de nova categoria
    fetchCategorias();  // Recarrega as categorias
    isCreatingCategory.value = false;  // Fecha o formulário de criação de categoria
    errorMessage.value = "";
  } catch (error) {
    errorMessage.value = "Erro ao criar nova categoria.";
  }
};

// Função para alternar criação de categoria
const toggleCreateCategory = () => {
  isCreatingCategory.value = !isCreatingCategory.value;
};

onMounted(() => {
  fetchCategorias();
  fetchMovimentacoes();
});

const logout = () => {
  // Remove o token de autenticação do localStorage
  localStorage.removeItem('token');

  // Redireciona o usuário para a página de login
  router.push('/login');
};
</script>

<style scoped>
/* Seu estilo aqui */
</style>
