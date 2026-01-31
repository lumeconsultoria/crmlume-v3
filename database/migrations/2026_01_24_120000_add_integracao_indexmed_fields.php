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
        // Grupos
        Schema::table('grupos', function (Blueprint $table) {
            $table->unsignedBigInteger('indexmed_id')->nullable()->after('id');
            $table->string('codigo_externo', 50)->nullable()->after('indexmed_id');
            $table->char('status_integracao', 1)->nullable()->after('ativo'); // A/I
        });

        // Empresas
        Schema::table('empresas', function (Blueprint $table) {
            $table->unsignedBigInteger('indexmed_id')->nullable()->after('id');
            $table->string('codigo_externo', 50)->nullable()->after('indexmed_id');
            $table->char('status_integracao', 1)->nullable()->after('ativo'); // A/I/F/T
            $table->string('nr_cnpj', 20)->nullable()->after('nome');
            $table->string('cd_cnae', 10)->nullable()->after('nr_cnpj');
            $table->unsignedTinyInteger('nr_grau_risco')->nullable()->after('cd_cnae');
            $table->string('ds_telefone', 20)->nullable()->after('nr_grau_risco');
        });

        // Unidades
        Schema::table('unidades', function (Blueprint $table) {
            $table->unsignedBigInteger('indexmed_id')->nullable()->after('id');
            $table->string('codigo_externo', 50)->nullable()->after('indexmed_id'); // cd_interno_unidade
            $table->char('status_integracao', 1)->nullable()->after('ativo'); // A/I/F/T
            $table->string('nr_cnpj', 20)->nullable()->after('nome');
            $table->string('cd_cnae', 10)->nullable()->after('nr_cnpj');
            $table->unsignedTinyInteger('nr_grau_risco')->nullable()->after('cd_cnae');
            $table->string('ds_telefone', 20)->nullable()->after('nr_grau_risco');
        });

        // Setores
        Schema::table('setores', function (Blueprint $table) {
            $table->unsignedBigInteger('indexmed_id')->nullable()->after('id');
            $table->string('codigo_externo', 50)->nullable()->after('indexmed_id'); // cd_interno_setor
            $table->char('status_integracao', 1)->nullable()->after('ativo'); // A/I
            $table->text('descricao')->nullable()->after('nome');
        });

        // Funcoes
        Schema::table('funcoes', function (Blueprint $table) {
            $table->unsignedBigInteger('indexmed_id')->nullable()->after('id');
            $table->string('codigo_externo', 50)->nullable()->after('indexmed_id'); // cd_interno_funcao
            $table->char('status_integracao', 1)->nullable()->after('ativo'); // A/I
            $table->string('cd_cbo', 20)->nullable()->after('nome');
            $table->text('descricao')->nullable()->after('cd_cbo');
        });

        // Colaboradores
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->unsignedBigInteger('indexmed_id')->nullable()->after('id');
            $table->string('matricula', 50)->nullable()->after('indexmed_id');
            $table->string('cpf', 14)->nullable()->after('matricula');
            $table->string('genero', 20)->nullable()->after('cpf');
            $table->date('data_admissao')->nullable()->after('data_nascimento');
            $table->string('user_email')->nullable()->after('genero');
            $table->boolean('user_ativo')->default(true)->after('user_email');
            $table->timestamp('email_validado_em')->nullable()->after('user_ativo');
            $table->char('status_integracao', 1)->nullable()->after('ativo'); // A/I/F/T
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->dropColumn(['indexmed_id', 'codigo_externo', 'status_integracao']);
        });

        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['indexmed_id', 'codigo_externo', 'status_integracao', 'nr_cnpj', 'cd_cnae', 'nr_grau_risco', 'ds_telefone']);
        });

        Schema::table('unidades', function (Blueprint $table) {
            $table->dropColumn(['indexmed_id', 'codigo_externo', 'status_integracao', 'nr_cnpj', 'cd_cnae', 'nr_grau_risco', 'ds_telefone']);
        });

        Schema::table('setores', function (Blueprint $table) {
            $table->dropColumn(['indexmed_id', 'codigo_externo', 'status_integracao', 'descricao']);
        });

        Schema::table('funcoes', function (Blueprint $table) {
            $table->dropColumn(['indexmed_id', 'codigo_externo', 'status_integracao', 'cd_cbo', 'descricao']);
        });

        Schema::table('colaboradores', function (Blueprint $table) {
            $table->dropColumn([
                'indexmed_id',
                'matricula',
                'cpf',
                'genero',
                'data_admissao',
                'user_email',
                'user_ativo',
                'email_validado_em',
                'status_integracao',
            ]);
        });
    }
};
