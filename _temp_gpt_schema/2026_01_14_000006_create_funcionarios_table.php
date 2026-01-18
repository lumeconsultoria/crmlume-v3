<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('unidade_id')->constrained('unidades')->cascadeOnDelete();
            $table->foreignId('setor_id')->constrained('setores')->cascadeOnDelete();
            $table->foreignId('funcao_id')->constrained('funcoes')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Vínculo autenticação
            $table->unsignedBigInteger('external_id')->nullable()->index(); // Id (planilha)
            $table->string('nome_funcionario', 255)->index(); // Índice para busca direta
            $table->string('matricula', 60)->nullable()->index();
            $table->string('cpf', 14)->nullable()->index();
            $table->string('nr_esocial', 60)->nullable()->unique(); // e-Social único global
            $table->string('genero', 120)->nullable();
            $table->string('grupo_cliente', 255)->nullable();
            $table->string('empresa_empregador', 255)->nullable();
            $table->string('unidade_estabelecimento', 255)->nullable();
            $table->string('setor', 255)->nullable();
            $table->string('funcao', 255)->nullable();
            $table->string('ghe', 120)->nullable();
            $table->date('data_de_nascimento')->nullable();
            $table->date('data_de_admissao')->nullable();
            $table->date('data_da_ultima_avaliacao_clinica')->nullable();
            $table->date('data_do_proximo_exame')->nullable();
            $table->string('status', 120)->nullable();
            $table->dateTime('cadastrado_em')->nullable();
            $table->string('cadastrado_por', 120)->nullable();
            $table->dateTime('ultima_vez_modificado_em')->nullable();
            $table->string('ultima_vez_modificado_por', 120)->nullable();
            $table->index('cpf'); // CPF pode repetir (trabalha em várias empresas)
            $table->unique(['empresa_id','matricula']);
            $table->index(['grupo_id','empresa_id','unidade_id','setor_id','funcao_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
