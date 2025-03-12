<template>
  <v-container class="dashboard">
    <!-- Botão de Logout -->
    <v-row justify="end">
      <v-btn color="red" @click="logout" class="mb-4">Logout</v-btn>
    </v-row>

    <h2 class="text-h4 text-center mb-4">Dashboard de Movimentações</h2>

    <!-- Formulário para adicionar movimentação -->
    <v-form ref="form" v-model="valid" @submit.prevent="addMovimentacao">
      <v-card class="pa-4 mb-4">
        <v-card-title>
          {{ editMode ? "Editar Movimentação" : "Adicionar Movimentação" }}
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="novaMovimentacao.date"
                label="Data"
                type="date"
                :rules="[(v) => !!v || 'Data é obrigatória']"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                v-model="novaMovimentacao.type"
                label="Tipo"
                :items="['entrada', 'saida']"
                :rules="[(v) => !!v || 'Tipo é obrigatório']"
                required
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="novaMovimentacao.value"
                label="Valor"
                type="number"
                step="0.01"
                :rules="[
                  (v) => !!v || 'Valor é obrigatório',
                  (v) => v > 0 || 'Valor deve ser maior que zero',
                ]"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                v-model="novaMovimentacao.category"
                label="Categoria"
                :items="categorias.map((c) => c.nome)"
                :rules="[(v) => !!v || 'Categoria é obrigatória']"
                required
              ></v-select>
            </v-col>
            <v-col cols="12">
              <v-textarea
                v-model="novaMovimentacao.description"
                label="Descrição"
                :rules="[(v) => !!v || 'Descrição é obrigatória']"
                required
              ></v-textarea>
            </v-col>
          </v-row>

          <!-- Botão de Criar Categoria -->
          <v-btn color="primary" @click="toggleCreateCategory" class="mb-3">
            Criar Nova Categoria
          </v-btn>

          <v-expand-transition>
            <div v-if="isCreatingCategory">
              <v-text-field
                v-model="novaCategoria"
                label="Nome da Nova Categoria"
                :rules="[(v) => !!v || 'Nome da categoria é obrigatório']"
              ></v-text-field>
              <v-btn
                color="green"
                @click="createCategory"
                :disabled="!novaCategoria"
              >
                Criar
              </v-btn>
            </div>
          </v-expand-transition>
        </v-card-text>

        <v-card-actions>
          <v-btn
            color="green"
            type="submit"
            :disabled="!valid || loading"
            :loading="loading"
          >
            {{ editMode ? "Atualizar Movimentação" : "Adicionar Movimentação" }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>

    <!-- Tabela de Movimentações -->
    <v-data-table
      v-if="movimentacoes.length > 0"
      :items="movimentacoes"
      :headers="headers"
      class="elevation-2"
    >
      <template v-slot:item="{ item }">
        <tr>
          <td>{{ formatDate(item.date) }}</td>
          <td>{{ item.type }}</td>
          <td>R$ {{ formatValue(item.value) }}</td>
          <td>{{ item.category }}</td>
          <td>{{ item.description }}</td>
          <td>
            <v-btn
              icon="mdi-pencil"
              size="small"
              color="blue"
              class="mr-2"
              @click="editMovimentacao(item)"
            ></v-btn>
            <v-btn
              icon="mdi-delete"
              size="small"
              color="red"
              @click="deleteMovimentacao(item.id)"
            ></v-btn>
          </td>
        </tr>
      </template>
    </v-data-table>

    <!-- Mensagens -->
    <v-alert v-if="errorMessage" type="error" class="mt-3">
      {{ errorMessage }}
    </v-alert>
    <v-alert v-if="successMessage" type="success" class="mt-3">
      {{ successMessage }}
    </v-alert>
    <v-alert
      v-if="movimentacoes.length === 0 && !errorMessage"
      type="info"
      class="mt-3"
    >
      Nenhuma movimentação encontrada.
    </v-alert>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();
const form = ref(null);
const valid = ref(false);
const loading = ref(false);
const successMessage = ref("");

const headers = [
  { title: "Data de Criação", key: "date" },
  { title: "Tipo", key: "type" },
  { title: "Valor (R$)", key: "value" },
  { title: "Categoria", key: "category" },
  { title: "Descrição", key: "description" },
  { title: "Ações", key: "actions", sortable: false },
];

const movimentacoes = ref([]);
const categorias = ref([]);
const novaMovimentacao = ref({
  date: "",
  type: "",
  value: 0,
  category: "",
  description: "",
});
const novaCategoria = ref("");
const isCreatingCategory = ref(false);
const errorMessage = ref("");
const editMode = ref(false);
const currentMovimentacao = ref(null);

const formatDate = (date) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString("pt-BR");
};

const formatValue = (value) => {
  if (!value) return "0,00";
  return parseFloat(value).toFixed(2);
};

const clearMessages = () => {
  errorMessage.value = "";
  successMessage.value = "";
};

// Função para carregar as categorias
const fetchCategorias = async () => {
  const token = localStorage.getItem("token");
  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/categorias", {
      headers: { Authorization: `Bearer ${token}` },
    });
    categorias.value = response.data;
  } catch (error) {
    errorMessage.value = `Erro ao carregar categorias: ${
      error.response?.data?.message || error.message
    }`;
  }
};

// Função para carregar as movimentações
const fetchMovimentacoes = async () => {
  const token = localStorage.getItem("token");
  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/movimentacoes", {
      headers: { Authorization: `Bearer ${token}` },
    });
    movimentacoes.value = response.data.map((item) => ({
      date: item.date,
      type: item.type,
      value: item.value,
      category: item.category,
      description: item.description,
      id: item.id,
    }));
  } catch (error) {
    errorMessage.value = `Erro ao carregar movimentações: ${
      error.response?.data?.message || error.message
    }`;
  }
};

// Função para adicionar ou editar movimentação
const addMovimentacao = async () => {
  if (!form.value.validate()) return;

  const token = localStorage.getItem("token");
  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }

  clearMessages();
  loading.value = true;

  try {
    if (editMode.value) {
      await axios.put(
        `http://127.0.0.1:8000/api/movimentacoes/${novaMovimentacao.value.id}`,
        novaMovimentacao.value,
        { headers: { Authorization: `Bearer ${token}` } }
      );
      successMessage.value = "Movimentação atualizada com sucesso!";
      editMode.value = false;
      currentMovimentacao.value = null;
    } else {
      await axios.post(
        "http://127.0.0.1:8000/api/movimentacoes",
        novaMovimentacao.value,
        { headers: { Authorization: `Bearer ${token}` } }
      );
      successMessage.value = "Movimentação adicionada com sucesso!";
    }

    form.value.reset();
    novaMovimentacao.value = {
      date: "",
      type: "",
      value: 0,
      category: "",
      description: "",
    };
    fetchMovimentacoes();
  } catch (error) {
    errorMessage.value = `Erro: ${
      error.response?.data?.message || "Erro ao processar a movimentação"
    }`;
  } finally {
    loading.value = false;
  }
};

// Função para editar movimentação
const editMovimentacao = (mov) => {
  editMode.value = true;
  currentMovimentacao.value = mov;
  novaMovimentacao.value = { ...mov };
  clearMessages();
};

// Função para deletar movimentação
const deleteMovimentacao = async (id) => {
  if (!confirm("Tem certeza que deseja excluir esta movimentação?")) return;

  const token = localStorage.getItem("token");
  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }

  clearMessages();
  loading.value = true;

  try {
    await axios.delete(`http://127.0.0.1:8000/api/movimentacoes/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    });
    successMessage.value = "Movimentação excluída com sucesso!";
    fetchMovimentacoes();
  } catch (error) {
    errorMessage.value = `Erro ao deletar movimentação: ${
      error.response?.data?.message || error.message
    }`;
  } finally {
    loading.value = false;
  }
};

// Função para criar uma nova categoria
const createCategory = async () => {
  if (!novaCategoria.value) return;

  const token = localStorage.getItem("token");
  if (!token) {
    errorMessage.value = "Token de autenticação não encontrado!";
    return;
  }

  clearMessages();
  loading.value = true;

  try {
    await axios.post(
      "http://127.0.0.1:8000/api/categorias",
      { nome: novaCategoria.value },
      { headers: { Authorization: `Bearer ${token}` } }
    );
    successMessage.value = "Categoria criada com sucesso!";
    novaCategoria.value = "";
    fetchCategorias();
    isCreatingCategory.value = false;
  } catch (error) {
    errorMessage.value = `Erro ao criar categoria: ${
      error.response?.data?.message || error.message
    }`;
  } finally {
    loading.value = false;
  }
};

// Função para alternar o estado de criar nova categoria
const toggleCreateCategory = () => {
  isCreatingCategory.value = !isCreatingCategory.value;
  clearMessages();
};

// Função de logout
const logout = async () => {
  const token = localStorage.getItem("token");
  if (!token) {
    router.push("/");
    return;
  }

  try {
    await axios.post(
      "http://127.0.0.1:8000/api/logout",
      {},
      { headers: { Authorization: `Bearer ${token}` } }
    );
    localStorage.removeItem("token");
    router.push("/");
  } catch (error) {
    errorMessage.value = `Erro ao fazer logout: ${
      error.response?.data?.message || error.message
    }`;
  }
};

// Função chamada quando o componente for montado
onMounted(() => {
  fetchCategorias();
  fetchMovimentacoes();
});
</script>

<style scoped>
.dashboard {
  max-width: 1200px;
  margin: 0 auto;
}

.mr-2 {
  margin-right: 8px;
}

.v-data-table {
  width: 100%;
}
</style>
