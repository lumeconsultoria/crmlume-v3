<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('tipo_documento', 10)->nullable();
            $table->string('documento', 20)->nullable();
            $table->string('cnae', 20)->nullable();
            $table->string('atividade', 255)->nullable();
            $table->string('grau_risco', 50)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('logradouro', 255)->nullable();
            $table->string('bairro', 120)->nullable();
            $table->string('cidade', 120)->nullable();
            $table->string('uf', 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_documento',
                'documento',
                'cnae',
                'atividade',
                'grau_risco',
                'cep',
                'logradouro',
                'bairro',
                'cidade',
                'uf',
            ]);
        });
    }
};
