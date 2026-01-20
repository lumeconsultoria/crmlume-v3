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
        Schema::create('assinatura_relatorio_pontos', function (Blueprint $table) {
            $table->id();
            $table->string('hash_documento', 64);
            $table->string('algoritmo', 20)->default('sha256');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->date('periodo_inicio');
            $table->date('periodo_fim');
            $table->string('arquivo_path')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['periodo_inicio', 'periodo_fim']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assinatura_relatorio_pontos');
    }
};
