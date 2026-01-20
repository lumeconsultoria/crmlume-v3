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
        Schema::create('ajuste_pontos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_ponto_id')->constrained('registro_pontos')->onDelete('restrict');
            $table->string('motivo', 255);
            $table->foreignId('alterado_por_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuste_pontos');
    }
};
