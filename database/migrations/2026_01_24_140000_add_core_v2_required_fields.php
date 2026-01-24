<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Grupos
        Schema::table('grupos', function (Blueprint $table) {
            if (!Schema::hasColumn('grupos', 'nr_cnpj')) {
                $table->string('nr_cnpj', 20)->nullable()->after('codigo_externo');
            }
        });

        // Empresas
        Schema::table('empresas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas', 'nm_razao_social')) {
                $table->string('nm_razao_social')->nullable()->after('grupo_id');
            }
            if (!Schema::hasColumn('empresas', 'nm_fantasia')) {
                $table->string('nm_fantasia')->nullable()->after('nm_razao_social');
            }
            if (!Schema::hasColumn('empresas', 'nr_cnpj')) {
                $table->string('nr_cnpj', 20)->nullable()->after('nm_fantasia');
            }
            if (!Schema::hasColumn('empresas', 'cd_cnae')) {
                $table->string('cd_cnae', 10)->nullable()->after('nr_cnpj');
            }
            if (!Schema::hasColumn('empresas', 'nr_grau_risco')) {
                $table->unsignedTinyInteger('nr_grau_risco')->nullable()->after('cd_cnae');
            }
            if (!Schema::hasColumn('empresas', 'ds_telefone')) {
                $table->string('ds_telefone', 20)->nullable()->after('nr_grau_risco');
            }
            // Endereço
            foreach ([
                'ds_cep' => 10,
                'ds_logradouro' => 140,
                'ds_numero' => 20,
                'ds_complemento' => 60,
                'ds_bairro' => 80,
                'ds_cidade' => 80,
                'sgl_estado' => 2,
            ] as $col => $len) {
                if (!Schema::hasColumn('empresas', $col)) {
                    $table->string($col, $len)->nullable()->after('ds_telefone');
                }
            }
        });

        // Unidades
        Schema::table('unidades', function (Blueprint $table) {
            if (!Schema::hasColumn('unidades', 'nm_fantasia')) {
                $table->string('nm_fantasia')->nullable()->after('empresa_id');
            }
            if (!Schema::hasColumn('unidades', 'nr_cnpj')) {
                $table->string('nr_cnpj', 20)->nullable()->after('nm_fantasia');
            }
            if (!Schema::hasColumn('unidades', 'cd_cnae')) {
                $table->string('cd_cnae', 10)->nullable()->after('nr_cnpj');
            }
            if (!Schema::hasColumn('unidades', 'nr_grau_risco')) {
                $table->unsignedTinyInteger('nr_grau_risco')->nullable()->after('cd_cnae');
            }
            if (!Schema::hasColumn('unidades', 'ds_telefone')) {
                $table->string('ds_telefone', 20)->nullable()->after('nr_grau_risco');
            }
            foreach ([
                'ds_cep' => 10,
                'ds_logradouro' => 140,
                'ds_numero' => 20,
                'ds_complemento' => 60,
                'ds_bairro' => 80,
                'ds_cidade' => 80,
                'sgl_estado' => 2,
            ] as $col => $len) {
                if (!Schema::hasColumn('unidades', $col)) {
                    $table->string($col, $len)->nullable()->after('ds_telefone');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            if (Schema::hasColumn('grupos', 'nr_cnpj')) {
                $table->dropColumn('nr_cnpj');
            }
        });

        Schema::table('empresas', function (Blueprint $table) {
            $drop = [
                'nm_razao_social',
                'nm_fantasia',
                'nr_cnpj',
                'cd_cnae',
                'nr_grau_risco',
                'ds_telefone',
                'ds_cep',
                'ds_logradouro',
                'ds_numero',
                'ds_complemento',
                'ds_bairro',
                'ds_cidade',
                'sgl_estado',
            ];
            foreach ($drop as $col) {
                if (Schema::hasColumn('empresas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('unidades', function (Blueprint $table) {
            $drop = [
                'nm_fantasia',
                'nr_cnpj',
                'cd_cnae',
                'nr_grau_risco',
                'ds_telefone',
                'ds_cep',
                'ds_logradouro',
                'ds_numero',
                'ds_complemento',
                'ds_bairro',
                'ds_cidade',
                'sgl_estado',
            ];
            foreach ($drop as $col) {
                if (Schema::hasColumn('unidades', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
