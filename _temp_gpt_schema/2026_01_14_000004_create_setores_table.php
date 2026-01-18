<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('unidade_id')->nullable()->constrained('unidades')->nullOnDelete();
            $table->unsignedBigInteger('cd_setor');
            $table->unsignedBigInteger('cd_empresa')->nullable();
            $table->unsignedBigInteger('cd_grupo')->nullable();
            $table->foreign('cd_empresa')->references('cd_empresa')->on('empresas');
            $table->foreign('cd_grupo')->references('cd_grupo')->on('grupos');
            $table->string('nm_setor', 255)->nullable();
            $table->longText('ds_setor')->nullable();
            $table->string('cd_centro_custo', 60)->nullable();
            $table->string('cd_interno_setor', 60)->nullable();
            $table->string('nm_empresa', 255)->nullable();
            $table->string('nm_unidade', 255)->nullable();
            $table->string('nm_grupo', 255)->nullable();
            $table->string('cd_tipo_edificacao', 255)->nullable();
            $table->string('cd_tipo_fechamento', 255)->nullable();
            $table->string('cd_tipo_piso', 255)->nullable();
            $table->string('cd_tipo_iluminacao', 255)->nullable();
            $table->string('cd_tipo_ventilacao', 255)->nullable();
            $table->string('cd_tipo_cobertura', 255)->nullable();
            $table->decimal('nr_metro_quadrado', 12, 2)->nullable();
            $table->decimal('nr_altura_pe_direito', 12, 2)->nullable();
            $table->string('ds_status', 40)->nullable();
            $table->string('fl_status', 40)->nullable();
            $table->string('fl_ds_setor_esocial', 40)->nullable();
            $table->unsignedBigInteger('cd_user_cadm')->nullable();
            $table->string('nm_user_cadm', 255)->nullable();
            $table->dateTime('ts_user_cadm')->nullable();
            $table->unsignedBigInteger('cd_user_manu')->nullable();
            $table->string('nm_user_manu', 255)->nullable();
            $table->dateTime('ts_user_manu')->nullable();
            $table->unique(['cd_empresa','cd_setor']);
            $table->index(['empresa_id','unidade_id']);
            $table->index('fl_status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setores');
    }
};
