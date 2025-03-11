<template>
    <div class="category-form">
      <input v-model="newCategory" type="text" placeholder="Nome da nova categoria" />
      <button @click="createCategory">Salvar Categoria</button>
      <button @click="cancelForm">Cancelar</button>
  
      <!-- Exibir mensagens de erro -->
      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  
  const newCategory = ref('');
  const errorMessage = ref('');
  
  const createCategory = async () => {
    const token = localStorage.getItem("token");
  
    if (!token) {
      errorMessage.value = "Token de autenticação não encontrado!";
      return;
    }
  
    if (!newCategory.value) {
      errorMessage.value = "O nome da categoria é obrigatório!";
      return;
    }
  
    try {
      const response = await axios.post(
        "http://127.0.0.1:8000/api/categorias",
        { nome: newCategory.value },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
  
      alert(response.data.message); // Mensagem de sucesso
      newCategory.value = ''; // Limpar o campo de entrada após o sucesso
      errorMessage.value = ''; // Limpar a mensagem de erro
    } catch (error) {
      console.error("Erro ao criar categoria:", error);
      errorMessage.value = "Erro ao criar categoria.";
    }
  };
  
  // Função para cancelar a criação de categoria
  const cancelForm = () => {
    newCategory.value = '';
    errorMessage.value = '';
  };
  </script>
  
  <style scoped>
  .category-form {
    margin-top: 20px;
  }
  
  .category-form input {
    padding: 8px;
    margin-right: 10px;
    border: 1px solid #ccc;
  }
  
  .category-form button {
    background-color: #42b983;
    color: white;
    padding: 8px 12px;
    border: none;
  }
  
  .error {
    color: red;
    font-size: 14px;
  }
  </style>
  