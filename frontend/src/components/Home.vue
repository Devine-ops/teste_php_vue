<template>
  <v-app>
    
    <v-container
      fluid
      class="presentation-container"
    >
      
      <v-row
        align="center"
        justify="center"
        class="full-height"
      >
        <v-col cols="12" sm="8" md="6" lg="4">
          <v-card class="pa-5" outlined>
            <v-card-title class="text-center">
              <h2>Bem-vindo ao MONEY FLOW</h2>
            </v-card-title>
            <v-card-subtitle class="text-center">
              <p>Faça login ou crie uma conta para começar a usar.</p>
            </v-card-subtitle>

            <!-- Texto explicativo -->
            <v-card-text class="text-center explanation-text">
              <p>
                O MONEY FLOW é um web app que consolida suas movimentações bancárias de forma simples. 
                O objetivo é simplificar a gestão das suas finanças de forma eficiente.
                Evite imprevistos e gerencie suas finanças, vem pra MONEY FLOW!
              </p>
            </v-card-text>

            <v-card-actions class="justify-center">
              <v-btn color="success" to="/login">Entrar</v-btn>
              <v-btn color="black" to="/register">Criar Conta</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<style scoped>

.presentation-container {
  background-image: url('../assets/bg.png'); 
  background-size: cover;
  background-position: center;
  height: 100vh; 
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  color: white;
}

.full-height {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

v-row, v-col {
  width: 100%;
}

.explanation-text {
  background-color: rgba(255, 255, 255, 0.7); 
  padding: 20px;
  border-radius: 8px;
  color: #4CAF50; 
  margin-top: 20px;
}


.v-card--variant-elevated, .v-card--variant-flat {
  background-color: rgba(255, 255, 255, 0.8); 
  color:black; 
}


.v-btn.success {
  background-color: #4CAF50; 
  color: white;
}

.v-btn.yellow {
  background-color: #FFEB3B; 
  color: black;
}

/* Ajustes de responsividade */
@media (max-width: 768px) {
  .presentation-container {
    padding: 10px; 
  }

  .v-card {
    padding: 20px;
  }

  .explanation-text {
    font-size: 14px; 
    padding: 15px;
  }

  .v-card-title h2 {
    font-size: 24px; 
  }

  .v-card-subtitle p {
    font-size: 16px; 
  }
}
</style>
