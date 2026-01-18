<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->cascadeOnDelete();
            $table->unsignedBigInteger('cd_empresa');
            $table->unsignedBigInteger('cd_grupo');
            $table->foreign('cd_grupo')->references('cd_grupo')->on('grupos')->cascadeOnDelete();
            $table->string('rownumber', 255)->nullable();
            $table->string('nm_grupo', 255)->nullable();
            $table->string('nm_razao_social', 255)->nullable();
            $table->string('nm_fantasia', 255)->nullable();
            $table->string('nr_cnpj', 20)->unique(); // CNPJ único - não pode duplicar empresa
            $table->string('nr_cnpj_frm', 20)->nullable();
            $table->string('cd_tipo_inscricao', 255)->nullable();
            $table->string('ds_tipo_inscricao', 60)->nullable();
            $table->string('cd_cnae', 60)->nullable();
            $table->string('ds_cnae', 255)->nullable();
            $table->unsignedInteger('nr_grau_risco')->nullable();
            $table->string('ds_logradouro', 255)->nullable();
            $table->string('ds_numero', 255)->nullable();
            $table->string('ds_complemento', 255)->nullable();
            $table->string('ds_bairro', 255)->nullable();
            $table->string('ds_cep', 10)->nullable();
            $table->unsignedBigInteger('cd_estado')->nullable();
            $table->unsignedBigInteger('cd_cidade')->nullable();
            $table->string('ds_website', 255)->nullable();
            $table->string('fl_matriz', 40)->nullable();
            $table->string('nm_medico_coordenador', 255)->nullable();
            $table->string('nr_crm', 255)->nullable();
            $table->unsignedBigInteger('cd_estado_medico')->nullable();
            $table->string('sgl_estado_medico', 255)->nullable();
            $table->string('nr_cpf_medico', 20)->nullable();
            $table->string('ds_telefone', 60)->nullable();
            $table->string('ds_status_medico', 40)->nullable();
            $table->string('ds_status', 40)->nullable();
            $table->string('status_matriz', 40)->nullable();
            $table->string('fl_regra_padrao_pcmso', 40)->nullable();
            $table->string('sgl_estado', 2)->nullable();
            $table->string('nm_cidade', 255)->nullable();
            $table->unsignedBigInteger('cd_grupo_esocial')->nullable();
            $table->string('fl_status', 40)->nullable();
            $table->unsignedBigInteger('cd_user_cadm')->nullable();
            $table->string('nm_user_cadm', 255)->nullable();
            $table->dateTime('ts_user_cadm')->nullable();
            $table->unsignedBigInteger('cd_user_manu')->nullable();
            $table->string('nm_user_manu', 255)->nullable();
            $table->dateTime('ts_user_manu')->nullable();
            $table->unique(['cd_grupo','cd_empresa']);
            $table->index('nr_cnpj');
            $table->index('cd_cnae');
            $table->index('fl_status');
            $table->index(['sgl_estado','nm_cidade']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
