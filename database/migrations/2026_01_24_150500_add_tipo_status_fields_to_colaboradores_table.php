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
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->char('fl_tipo', 1)->default('F')->after('indexmed_id');
            $table->boolean('trabalhador_sem_vinculo')->default(false)->after('fl_tipo');
            $table->date('ultima_avaliacao_clinica')->nullable()->after('data_admissao');
            $table->string('codigo_externo', 50)->nullable()->after('status_integracao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->dropColumn([
                'fl_tipo',
                'trabalhador_sem_vinculo',
                'ultima_avaliacao_clinica',
                'codigo_externo',
            ]);
        });
    }
};
