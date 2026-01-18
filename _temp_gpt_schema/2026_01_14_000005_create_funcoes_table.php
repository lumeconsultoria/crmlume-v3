<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('unidade_id')->nullable()->constrained('unidades')->nullOnDelete();
            $table->foreignId('setor_id')->nullable()->constrained('setores')->nullOnDelete();
            $table->unsignedBigInteger('cd_funcao');
            $table->unsignedBigInteger('cd_empresa')->nullable();
            $table->unsignedBigInteger('cd_grupo')->nullable();
            $table->foreign('cd_empresa')->references('cd_empresa')->on('empresas');
            $table->foreign('cd_grupo')->references('cd_grupo')->on('grupos');
            $table->string('nm_funcao', 255)->nullable();
            $table->string('cd_cbo', 10)->nullable();
            $table->longText('ds_funcao')->nullable();
            $table->string('cd_interno_funcao', 60)->nullable();
            $table->string('nm_empresa', 255)->nullable();
            $table->string('nm_unidade', 255)->nullable();
            $table->string('nm_grupo', 255)->nullable();
            $table->string('ds_status', 40)->nullable();
            $table->string('fl_status', 40)->nullable();
            $table->unsignedInteger('nr_funcionario')->nullable();
            $table->unsignedBigInteger('cd_user_cadm')->nullable();
            $table->string('nm_user_cadm', 255)->nullable();
            $table->dateTime('ts_user_cadm')->nullable();
            $table->unsignedBigInteger('cd_user_manu')->nullable();
            $table->string('nm_user_manu', 255)->nullable();
            $table->dateTime('ts_user_manu')->nullable();
            $table->unique(['cd_empresa','cd_funcao']);
            $table->index(['empresa_id','unidade_id','setor_id']);
            $table->index('cd_cbo');
            $table->index('fl_status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcoes');
    }
};
