<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_grupo')->unique();
            $table->string('nm_grupo', 255)->nullable();
            $table->string('nm_razao_social', 255)->nullable();
            $table->string('nr_cnpj', 20)->nullable();
            $table->string('ds_status', 40)->nullable();
            $table->string('fl_status', 40)->nullable();
            $table->string('fl_pre_agendamento', 40)->nullable();
            $table->string('ds_pre_agendamento', 255)->nullable();
            $table->string('fl_esocial_automatico', 40)->nullable();
            $table->string('ds_esocial_automatico', 255)->nullable();
            $table->date('dt_minima_eventos')->nullable();
            $table->unsignedBigInteger('cd_assinatura')->nullable();
            $table->unsignedBigInteger('cd_user_cadm')->nullable();
            $table->string('nm_user_cadm', 255)->nullable();
            $table->dateTime('ts_user_cadm')->nullable();
            $table->unsignedBigInteger('cd_user_manu')->nullable();
            $table->string('nm_user_manu', 255)->nullable();
            $table->dateTime('ts_user_manu')->nullable();
            $table->index('nr_cnpj');
            $table->index('fl_status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
