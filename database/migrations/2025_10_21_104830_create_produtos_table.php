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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('categoria', 50)->nullable();
            $table->string('unidade', 20)->nullable();
            $table->decimal('quantidade', 10, 2)->default(0);
            $table->decimal('min_quantidade', 10, 2)->default(0);
            $table->date('data_validade')->nullable();
            $table->unsignedBigInteger('fornecedor_id')->nullable();
            $table->decimal('preco_custo', 10, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fornecedor_id')
                  ->references('id')
                  ->on('fornecedores')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
