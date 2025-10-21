<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id'); // Produto relacionado
            $table->decimal('quantidade', 10, 2); // Quantidade adicionada
            $table->date('data'); // Data da entrada
            $table->decimal('preco_custo', 10, 2)->nullable(); // Preço de custo do item
            $table->string('observacoes', 255)->nullable(); // Observações adicionais
            $table->timestamps();

            // $table->foreign('produto_id')
            //       ->references('id')
            //       ->on('produtos')
            //       ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
