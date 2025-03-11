<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacionamento com usuários
            $table->date('date'); // Data da movimentação
            $table->enum('type', ['entrada', 'saida']); // Tipo: entrada ou saída
            $table->decimal('value', 10, 2); // Valor da movimentação
            $table->string('category'); // Categoria (ex: alimentação, transporte)
            $table->text('description')->nullable(); // Descrição opcional
            $table->timestamps(); // Criação e atualização automática
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimentacoes');
    }
};

