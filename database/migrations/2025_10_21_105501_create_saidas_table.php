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
        Schema::create('saidas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id'); // Produto relacionado
            $table->decimal('quantidade', 10, 2); // Quantidade removida
            $table->date('data'); // Data da saída
            $table->string('motivo', 100)->nullable(); // Motivo da saída
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
        Schema::dropIfExists('saidas');
    }
};
