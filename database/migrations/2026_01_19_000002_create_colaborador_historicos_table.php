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
        Schema::create('colaborador_historicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('restrict');
            $table->foreignId('funcao_id_anterior')->nullable()->constrained('funcoes')->nullOnDelete();
            $table->foreignId('funcao_id_nova')->nullable()->constrained('funcoes')->nullOnDelete();
            $table->foreignId('unidade_id_anterior')->nullable()->constrained('unidades')->nullOnDelete();
            $table->foreignId('unidade_id_nova')->nullable()->constrained('unidades')->nullOnDelete();
            $table->foreignId('alterado_por_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('motivo', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaborador_historicos');
    }
};
