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
            $table->string('cpf_hash', 64)->nullable()->index();
            $table->date('data_nascimento')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->dropIndex(['cpf_hash']);
            $table->dropIndex(['data_nascimento']);
            $table->dropColumn(['cpf_hash', 'data_nascimento']);
        });
    }
};
